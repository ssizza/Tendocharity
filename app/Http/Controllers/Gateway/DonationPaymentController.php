<?php

namespace App\Http\Controllers\Gateway;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\AdminNotification;
use App\Models\CauseDonation;
use App\Models\Donor;
use App\Models\Fundraiser;
use App\Models\GatewayCurrency;
use Illuminate\Http\Request;
use App\Lib\ClientInfo;

class DonationPaymentController extends Controller
{
    public function initiateDonation($fundraiserSlug, Request $request)
    {
        $fundraiser = Fundraiser::where('slug', $fundraiserSlug)
            ->where('status', 'active')
            ->firstOrFail();
            
        $gatewayCurrency = GatewayCurrency::whereHas('method', function ($gate) {
            $gate->where('status', Status::ENABLE);
        })->with('method')->orderby('name')->get();
        
        $pageTitle = 'Donate to ' . $fundraiser->title;
        
        // Pre-fill data from query parameters
        $prefilledData = [
            'amount' => $request->get('amount', 50),
            'donor_name' => $request->get('donor_name', ''),
            'donor_email' => $request->get('donor_email', ''),
            'first_name' => '',
            'last_name' => '',
        ];
        
        // Split donor_name into first and last name if provided
        if (!empty($prefilledData['donor_name'])) {
            $nameParts = explode(' ', $prefilledData['donor_name'], 2);
            $prefilledData['first_name'] = $nameParts[0] ?? '';
            $prefilledData['last_name'] = $nameParts[1] ?? '';
        }
        
        return view('donations.payment.initiate', compact(
            'gatewayCurrency', 
            'pageTitle', 
            'fundraiser',
            'prefilledData'
        ));
    }

    public function insertDonation(Request $request, $fundraiserId)
    {
        // Basic validation
        $request->validate([
            'amount' => 'required|numeric|gt:0',
            'gateway' => 'required',
            'currency' => 'required',
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email|max:255',
        ]);

        $fundraiser = Fundraiser::findOrFail($fundraiserId);
        
        // Validate donation amount
        $minDonation = 1; // Minimum $1 donation
        if ($request->amount < $minDonation) {
            $notify[] = ['error', 'Minimum donation amount is $' . $minDonation];
            return back()->withNotify($notify);
        }

        $gate = GatewayCurrency::whereHas('method', function ($gate) {
            $gate->where('status', Status::ENABLE);
        })->where('method_code', $request->gateway)
        ->where('currency', $request->currency)
        ->first();
        
        if (!$gate) {
            $notify[] = ['error', 'Invalid payment gateway'];
            return back()->withNotify($notify);
        }

        if ($gate->min_amount > $request->amount || $gate->max_amount < $request->amount) {
            $notify[] = ['error', 'Please follow payment limits for this gateway'];
            return back()->withNotify($notify);
        }

        // Calculate charges
        $charge = $gate->fixed_charge + ($request->amount * $gate->percent_charge / 100);
        $payable = $request->amount + $charge;
        $finalAmount = $payable * $gate->rate;

        // Process checkbox values properly
        $receiveUpdates = $request->has('receive_updates') ? true : false;
        $isAnonymous = $request->has('is_anonymous') ? true : false;
        $taxDeductible = $request->has('tax_deductible') ? true : false;

        // Find or create donor
        $donor = Donor::firstOrCreate(
            ['email' => $request->email],
            [
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'phone' => $request->phone ?? '',
                'country' => $request->country ?? '',
                'city' => $request->city ?? '',
                'address' => $request->address ?? '',
                'postal_code' => $request->postal_code ?? '',
                'receive_updates' => $receiveUpdates,
            ]
        );

        // Get client info
        $clientInfo = ClientInfo::osBrowser();
        $ipInfo = ClientInfo::ipInfo();

        // Create donation record
        $donation = new CauseDonation();
        $donation->donor_id = $donor->id;
        $donation->fundraiser_id = $fundraiser->id;
        $donation->donor_name = $request->first_name . ' ' . $request->last_name;
        $donation->donor_email = $request->email;
        $donation->donor_phone = $request->phone ?? '';
        $donation->donor_address = $request->address ?? '';
        $donation->amount = $request->amount;
        $donation->currency = strtoupper($gate->currency);
        $donation->payment_method = $this->getPaymentMethodFromCode($gate->method_code);
        
        // FIX: Use string values for payment_status that match the enum
        $donation->payment_status = 'pending'; // Use 'pending' instead of Status::PAYMENT_INITIATE
        
        $donation->payment_reference = getTrx();
        $donation->is_anonymous = $isAnonymous;
        $donation->message = $request->message ?? '';
        $donation->tax_deductible = $taxDeductible;
        
        // Store metadata
        $metadata = [
            'gateway_code' => $gate->method_code,
            'gateway_name' => $gate->name,
            'charge' => $charge,
            'rate' => $gate->rate,
            'final_amount' => $finalAmount,
            'client_info' => [
                'country' => $ipInfo->country ?? 'Unknown',
                'city' => $ipInfo->city ?? 'Unknown',
                'timezone' => $ipInfo->timezone ?? 'Unknown',
            ]
        ];
        
        $donation->metadata = json_encode($metadata);
        $donation->ip_address = $ipInfo->ip ?? request()->ip();
        $donation->user_agent = $clientInfo['userAgent'] ?? request()->userAgent();
        $donation->browser = $clientInfo['browser'] ?? 'Unknown';
        $donation->os = $clientInfo['os'] ?? 'Unknown';
        
        $donation->save();

        // Store in session for payment confirmation
        session()->put('donation_track', $donation->payment_reference);
        session()->put('donation_id', $donation->id);
        
        return redirect()->route('donation.payment.confirm');
    }

    public function confirmPayment()
    {
        $track = session()->get('donation_track');
        $donationId = session()->get('donation_id');
        
        $donation = CauseDonation::where('payment_reference', $track)
            ->where('id', $donationId)
            ->where('payment_status', 'pending') // Changed from Status::PAYMENT_INITIATE
            ->with(['fundraiser', 'donor'])
            ->firstOrFail();
            
        $gateway = GatewayCurrency::where('method_code', json_decode($donation->metadata, true)['gateway_code'] ?? '')
            ->where('currency', $donation->currency)
            ->firstOrFail();

        // Manual payment methods (code >= 1000)
        if ($gateway->method_code >= 1000) {
            return redirect()->route('donation.payment.manual.confirm');
        }

        // Process via gateway
        $dirName = $gateway->method->alias;
        $new = __NAMESPACE__ . '\\' . $dirName . '\\ProcessController';

        if (!class_exists($new)) {
            $notify[] = ['error', 'Payment gateway not available'];
            return back()->withNotify($notify);
        }

        $data = $new::process($donation);
        $data = json_decode($data);

        if (isset($data->error)) {
            $notify[] = ['error', $data->message];
            return back()->withNotify($notify);
        }
        
        if (isset($data->redirect)) {
            return redirect($data->redirect_url);
        }

        // For Stripe V3
        if (@$data->session) {
            $donation->btc_wallet = $data->session->id;
            $donation->save();
        }

        $pageTitle = 'Confirm Donation Payment';
        return view("donations.payment.{$data->view}", compact('data', 'pageTitle', 'donation'));
    }

    public function success($reference)
    {
        $donation = CauseDonation::where('payment_reference', $reference)
            ->where('payment_status', Status::PAYMENT_SUCCESS)
            ->with(['fundraiser', 'donor'])
            ->firstOrFail();
            
        $pageTitle = 'Donation Successful';
        
        return view('donations.payment.success', compact('donation', 'pageTitle'));
    }

    public function cancel($reference)
    {
        $donation = CauseDonation::where('payment_reference', $reference)
            ->whereIn('payment_status', ['pending']) // Changed from array of Status constants
            ->first();
            
        if ($donation) {
            $donation->payment_status = 'failed'; // Changed from Status::PAYMENT_REJECTED
            $donation->save();
        }
        
        $pageTitle = 'Donation Cancelled';
        
        return view('donations.payment.cancel', compact('donation', 'pageTitle'));
    }

    public function manualConfirm()
    {
    $track = session()->get('donation_track');
    $donation = CauseDonation::where('payment_reference', $track)
        ->where('payment_status', 'pending') // Changed from Status::PAYMENT_INITIATE
        ->with(['fundraiser', 'donor'])
        ->firstOrFail();
            
        $gateway = GatewayCurrency::where('method_code', json_decode($donation->metadata, true)['gateway_code'] ?? '')
            ->firstOrFail();

        $pageTitle = 'Confirm Bank Transfer';
        $method = $gateway;
        $gatewayMethod = $gateway->method;
        
        return view('donations.payment.manual', compact('donation', 'pageTitle', 'method', 'gatewayMethod'));
    }

    public function manualUpdate(Request $request)
    {
        $request->validate([
            'transaction_id' => 'required|string|max:100',
            'payment_date' => 'required|date',
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg,pdf|max:2048',
        ]);

        $track = session()->get('donation_track');
        $donation = CauseDonation::where('payment_reference', $track)
            ->where('payment_status', 'pending') // Changed
            ->firstOrFail();

        // Upload payment proof
        if ($request->hasFile('payment_proof')) {
            $file = $request->file('payment_proof');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('assets/images/payment_proofs'), $filename);
            
            $paymentProof = 'assets/images/payment_proofs/' . $filename;
        }

        // Update donation with manual payment details
        $metadata = json_decode($donation->metadata, true);
        $metadata['manual_payment'] = [
            'transaction_id' => $request->transaction_id,
            'payment_date' => $request->payment_date,
            'payment_proof' => $paymentProof ?? null,
            'submitted_at' => now()->toDateTimeString(),
        ];
        
        $donation->metadata = json_encode($metadata);
        $donation->payment_status = 'pending'; // Keep as pending for manual verification
        $donation->save();

        // Create admin notification
        $adminNotification = new AdminNotification();
        $adminNotification->title = 'Manual donation payment submitted by ' . $donation->donor_name;
        $adminNotification->click_url = urlPath('admin.donations.details', $donation->id);
        $adminNotification->save();

        $notify[] = ['success', 'Your payment details have been submitted. We will verify and confirm your donation shortly.'];
        return redirect()->route('donation.success', $donation->payment_reference)->withNotify($notify);
    }

    public function checkDonationStatus($reference)
    {
        $donation = CauseDonation::where('payment_reference', $reference)
            ->with(['fundraiser'])
            ->firstOrFail();
            
        return response()->json([
            'success' => true,
            'status' => $donation->payment_status,
            'message' => $this->getStatusMessage($donation->payment_status),
            'donation' => [
                'reference' => $donation->payment_reference,
                'amount' => showAmount($donation->amount),
                'currency' => $donation->currency,
                'date' => $donation->created_at->format('M d, Y'),
                'fundraiser' => $donation->fundraiser->title ?? 'Unknown',
            ]
        ]);
    }

    public static function paymentSuccess($donation)
    {
        if ($donation->payment_status == 'pending') { // Changed from Status constants
            $donation->payment_status = 'completed'; // Changed from Status::PAYMENT_SUCCESS
            $donation->save();

            // Update fundraiser stats
            $fundraiser = Fundraiser::find($donation->fundraiser_id);
            if ($fundraiser) {
                $fundraiser->raised_amount += $donation->amount;
                $fundraiser->progress_percentage = ($fundraiser->raised_amount / $fundraiser->target_amount) * 100;
                $fundraiser->donors_count = CauseDonation::where('fundraiser_id', $fundraiser->id)
                    ->where('payment_status', 'completed') // Changed
                    ->distinct('donor_email')
                    ->count();
                $fundraiser->save();
            }

            // Update donor stats
            if ($donation->donor_id) {
                $donor = Donor::find($donation->donor_id);
                if ($donor) {
                    $donor->total_donations += 1;
                    $donor->total_amount += $donation->amount;
                    $donor->last_donation_at = now();
                    $donor->save();
                }
            }

            // Send receipt email
            self::sendDonationReceipt($donation);

            // Create admin notification
            $adminNotification = new AdminNotification();
            $adminNotification->title = 'New donation received from ' . $donation->donor_name;
            $adminNotification->click_url = urlPath('admin.donations.details', $donation->id);
            $adminNotification->save();
        }
    }

    private function getPaymentMethodFromCode($code)
    {
        $methods = [
            'paypal' => 'digital_wallet',
            'stripe' => 'credit_card',
            'razorpay' => 'digital_wallet',
            'flutterwave' => 'credit_card',
            'mollie' => 'bank_transfer',
            'manual' => 'bank_transfer',
        ];
        
        return $methods[$code] ?? 'other';
    }

    private function getStatusMessage($status)
    {
        $messages = [
            'pending' => 'Payment pending',
            'completed' => 'Payment successful',
            'failed' => 'Payment failed',
            'refunded' => 'Payment refunded',
        ];
        
        return $messages[$status] ?? 'Unknown status';
    }

    private static function sendDonationReceipt($donation)
    {
        // Implement email sending logic here
        // You can use Laravel Mail or a notification system
        
        $donation->receipt_sent = true;
        $donation->save();
    }
}