<?php

namespace App\Http\Controllers\Gateway;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\AdminNotification;
use App\Models\CauseDonation;
use App\Models\Donor;
use App\Models\Fundraiser;
use App\Models\GatewayCurrency;
use App\Models\Form;
use Illuminate\Http\Request;
use App\Lib\ClientInfo;
use App\Traits\DonationPaymentUpdate;

class DonationPaymentController extends Controller
{
    use DonationPaymentUpdate;

    /**
     * Step 1: Show donation form with available payment gateways
     */
    public function initiateDonation($fundraiserSlug, Request $request)
    {
        $fundraiser = Fundraiser::where('slug', $fundraiserSlug)
            ->where('status', 'active')
            ->firstOrFail();
            
        $gatewayCurrency = GatewayCurrency::whereHas('method', function ($gate) {
            $gate->where('status', Status::ENABLE);
        })->with('method')->orderBy('name')->get();
        
        $pageTitle = 'Donate to ' . $fundraiser->title;
        
        return view('donation.payment.initiate', compact(
            'gatewayCurrency', 
            'pageTitle', 
            'fundraiser'
        ));
    }

    /**
     * Step 2: Create donation record after form submission
     */
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
        
        $minDonation = 1;
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

        // Process checkbox values
        $receiveUpdates = $request->has('receive_updates');
        $isAnonymous = $request->has('is_anonymous');
        $taxDeductible = $request->has('tax_deductible');

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
        $donation->payment_status = 'pending';
        $donation->payment_reference = $this->generateTransactionReference();
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

    /**
     * Step 3: Confirm payment and route to appropriate gateway
     */
    public function confirmPayment()
    {
        $track = session()->get('donation_track');
        $donationId = session()->get('donation_id');
        
        $donation = CauseDonation::where('payment_reference', $track)
            ->where('id', $donationId)
            ->where('payment_status', 'pending')
            ->with(['fundraiser', 'donor'])
            ->firstOrFail();
            
        $metadata = json_decode($donation->metadata, true);
        $gateway = GatewayCurrency::where('method_code', $metadata['gateway_code'] ?? '')
            ->where('currency', $donation->currency)
            ->with('method')
            ->firstOrFail();

        // Manual payment methods (code >= 1000)
        if ($gateway->method_code >= 1000) {
            return redirect()->route('donation.payment.manual');
        }

        // Process via automatic gateway
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

        if (isset($data->session)) {
            $metadata['stripe_session_id'] = $data->session->id;
            $donation->metadata = json_encode($metadata);
            $donation->save();
        }

        $pageTitle = 'Confirm Donation Payment';
        return view("donation.payment.{$data->view}", compact('data', 'pageTitle', 'donation'));
    }

    /**
     * Step 4: Show manual payment form with bank details
     */
    public function manualPayment()
    {
        $track = session()->get('donation_track');
        $donation = CauseDonation::where('payment_reference', $track)
            ->where('payment_status', 'pending')
            ->with(['fundraiser'])
            ->firstOrFail();
            
        $metadata = json_decode($donation->metadata, true);
        $gatewayCurrency = GatewayCurrency::where('method_code', $metadata['gateway_code'])
            ->where('currency', $donation->currency)
            ->with('method')
            ->firstOrFail();
            
        $pageTitle = 'Manual Payment - ' . $gatewayCurrency->method->name;
        
        // Get bank details from gateway_parameter
        $bankDetails = [];
        if ($gatewayCurrency->gateway_parameter) {
            $bankDetails = json_decode($gatewayCurrency->gateway_parameter, true) ?? [];
        }
        
        // Get form data if exists
        $form = null;
        if ($gatewayCurrency->method->form_id) {
            $form = Form::find($gatewayCurrency->method->form_id);
        }
        
        return view('donation.payment.manual', compact(
            'donation', 
            'pageTitle', 
            'gatewayCurrency',
            'bankDetails',
            'form'
        ));
    }

    /**
     * Step 5: Process manual payment submission with form data
     */
    public function manualPaymentSubmit(Request $request)
    {
        $track = session()->get('donation_track');
        $donation = CauseDonation::where('payment_reference', $track)
            ->where('payment_status', 'pending')
            ->firstOrFail();
            
        $metadata = json_decode($donation->metadata, true);
        $gatewayCurrency = GatewayCurrency::where('method_code', $metadata['gateway_code'])
            ->where('currency', $donation->currency)
            ->with('method')
            ->firstOrFail();

        // Build validation rules
        $validationRules = $this->buildValidationRules($gatewayCurrency);
        
        // Validate request
        $request->validate($validationRules);

        // Create upload directories
        $this->ensureUploadDirectoriesExist();

        // Handle file uploads
        $uploadedFiles = $this->handleFileUploads($request);

        // Process form fields
        $submittedFormData = $this->processFormFields($request, $uploadedFiles);

        // Handle payment proof upload
        $paymentProofPath = $this->uploadPaymentProof($request);

        // Update metadata with manual payment details
        $metadata['manual_payment'] = [
            'submitted_at' => now()->toDateTimeString(),
            'payment_proof' => $paymentProofPath,
            'form_data' => $submittedFormData,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ];
        
        $donation->metadata = json_encode($metadata);
        $donation->save();

        // Create admin notification
        $this->createAdminNotification($donation);

        // Send email notification
        $this->sendManualPaymentSubmittedEmail($donation);

        $notify[] = ['success', 'Your payment details have been submitted successfully. We will verify and confirm your donation shortly.'];
        
        return redirect()->route('donation.pending', $donation->payment_reference)->withNotify($notify);
    }

    /**
     * Build validation rules from form data
     */
    private function buildValidationRules($gatewayCurrency)
    {
        $validationRules = [];
        
        if ($gatewayCurrency->method->form_id) {
            $form = Form::find($gatewayCurrency->method->form_id);
            
            if ($form && $form->form_data) {
                // Get form fields - handle both string and already decoded data
                $formFields = $this->getFormFields($form->form_data);
                
                foreach ($formFields as $field) {
                    $fieldLabel = $this->getFieldLabel($field);
                    $isRequired = $this->getFieldRequirement($field);
                    $fieldType = $this->getFieldType($field);
                    
                    if (!$fieldLabel) continue;
                    
                    $rule = $isRequired;
                    $validationRules['form.' . $fieldLabel] = $rule;
                    
                    // Add file validation if type is file
                    if ($fieldType == 'file' && $rule == 'required') {
                        $validationRules['form.' . $fieldLabel] = 'required|file|max:5120|mimes:jpg,jpeg,png,pdf';
                    }
                }
            }
        }

        // Add payment proof validation
        $validationRules['payment_proof'] = 'required|file|mimes:jpg,jpeg,png,pdf|max:5120';
        
        return $validationRules;
    }

    /**
     * Get form fields safely - handles both string JSON and already decoded data
     */
    private function getFormFields($formData)
    {
        if (is_string($formData)) {
            return json_decode($formData, true) ?? [];
        }
        
        if (is_object($formData)) {
            return (array) $formData;
        }
        
        if (is_array($formData)) {
            return $formData;
        }
        
        return [];
    }

    /**
     * Get field label safely
     */
    private function getFieldLabel($field)
    {
        if (is_array($field)) {
            return $field['label'] ?? null;
        }
        
        if (is_object($field)) {
            return $field->label ?? null;
        }
        
        return null;
    }

    /**
     * Get field requirement safely
     */
    private function getFieldRequirement($field)
    {
        if (is_array($field)) {
            return $field['is_required'] ?? 'nullable';
        }
        
        if (is_object($field)) {
            return $field->is_required ?? 'nullable';
        }
        
        return 'nullable';
    }

    /**
     * Get field type safely
     */
    private function getFieldType($field)
    {
        if (is_array($field)) {
            return $field['type'] ?? 'text';
        }
        
        if (is_object($field)) {
            return $field->type ?? 'text';
        }
        
        return 'text';
    }

    /**
     * Ensure upload directories exist
     */
    private function ensureUploadDirectoriesExist()
    {
        $directories = [
            public_path('assets/uploads/payment_proofs'),
            public_path('assets/uploads/form_uploads')
        ];
        
        foreach ($directories as $path) {
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }
        }
    }

    /**
     * Handle file uploads from form fields
     */
    private function handleFileUploads(Request $request)
    {
        $uploadedFiles = [];
        
        if ($request->has('form')) {
            foreach ($request->form as $key => $value) {
                if ($request->hasFile('form.' . $key)) {
                    $file = $request->file('form.' . $key);
                    $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                    $file->move(public_path('assets/uploads/form_uploads'), $filename);
                    $uploadedFiles[$key] = 'assets/uploads/form_uploads/' . $filename;
                }
            }
        }
        
        return $uploadedFiles;
    }

    /**
     * Process form fields, replacing file inputs with paths
     */
    private function processFormFields(Request $request, array $uploadedFiles)
    {
        $formData = [];
        
        if ($request->has('form')) {
            foreach ($request->form as $key => $value) {
                if (isset($uploadedFiles[$key])) {
                    $formData[$key] = $uploadedFiles[$key];
                } else {
                    $formData[$key] = $value;
                }
            }
        }
        
        return $formData;
    }

    /**
     * Upload payment proof file
     */
    private function uploadPaymentProof(Request $request)
    {
        if ($request->hasFile('payment_proof')) {
            $file = $request->file('payment_proof');
            $filename = time() . '_proof_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('assets/uploads/payment_proofs'), $filename);
            return 'assets/uploads/payment_proofs/' . $filename;
        }
        
        return null;
    }

    /**
     * Create admin notification for new manual payment
     */
    private function createAdminNotification($donation)
    {
        $adminNotification = new AdminNotification();
        $adminNotification->title = 'Manual donation payment submitted - ' . $donation->donor_name . ' (' . $donation->payment_reference . ')';
        $adminNotification->click_url = 'admin/donations/details/' . $donation->id;
        $adminNotification->save();
    }

    /**
     * Legacy method for backward compatibility
     */
    public function manualConfirm()
    {
        return $this->manualPayment();
    }

    /**
     * Legacy method for backward compatibility
     */
    public function manualUpdate(Request $request)
    {
        return $this->manualPaymentSubmit($request);
    }

    /**
     * Show pending verification page
     */
    public function pending($reference)
    {
        $donation = CauseDonation::where('payment_reference', $reference)
            ->with(['fundraiser'])
            ->firstOrFail();
            
        $pageTitle = 'Donation Pending Verification';
        
        return view('donation.payment.pending', compact('donation', 'pageTitle'));
    }

    /**
     * Show success page
     */
    public function success($reference)
    {
        $donation = CauseDonation::where('payment_reference', $reference)
            ->where('payment_status', 'completed')
            ->with(['fundraiser', 'donor'])
            ->firstOrFail();
            
        $pageTitle = 'Donation Successful';
        
        return view('donation.payment.success', compact('donation', 'pageTitle'));
    }

    /**
     * Show cancelled page
     */
    public function cancel($reference)
    {
        $donation = CauseDonation::where('payment_reference', $reference)
            ->whereIn('payment_status', ['pending', 'failed'])
            ->first();
            
        $pageTitle = 'Donation Cancelled';
        
        return view('donation.payment.cancel', compact('donation', 'pageTitle'));
    }

    /**
     * Check donation status via AJAX
     */
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
                'amount' => number_format($donation->amount, 2),
                'currency' => $donation->currency,
                'date' => $donation->created_at->format('M d, Y'),
                'fundraiser' => $donation->fundraiser->title ?? 'Unknown',
            ]
        ]);
    }

    /**
     * Generate unique transaction reference
     */
    private function generateTransactionReference()
    {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $reference = '';
        
        for ($i = 0; $i < 12; $i++) {
            $reference .= $characters[rand(0, strlen($characters) - 1)];
        }
        
        // Ensure uniqueness
        while (CauseDonation::where('payment_reference', $reference)->exists()) {
            $reference = '';
            for ($i = 0; $i < 12; $i++) {
                $reference .= $characters[rand(0, strlen($characters) - 1)];
            }
        }
        
        return $reference;
    }

    /**
     * Send email notification for manual payment submission
     */
    private function sendManualPaymentSubmittedEmail($donation)
    {
        // TODO: Implement email sending using notification templates
        $donation->receipt_sent = true;
        $donation->save();
    }

    /**
     * Get payment method from gateway code
     */
    private function getPaymentMethodFromCode($code)
    {
        if ($code >= 1000) return 'bank_transfer';
        
        $methods = [
            'paypal' => 'digital_wallet',
            'stripe' => 'credit_card',
            'razorpay' => 'digital_wallet',
            'flutterwave' => 'credit_card',
            'mollie' => 'bank_transfer',
        ];
        
        return $methods[$code] ?? 'other';
    }

    /**
     * Get status message for API response
     */
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
}