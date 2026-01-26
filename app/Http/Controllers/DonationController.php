<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\Campaign;
use Illuminate\Http\Request;

class DonationController extends Controller
{
    public function create(Campaign $campaign)
    {
        return view('donations.create', compact('campaign'));
    }

    public function store(Request $request, Campaign $campaign)
    {
        $validated = $request->validate([
            'donor_name' => 'required|string|max:255',
            'donor_email' => 'required|email|max:255',
            'donor_phone' => 'nullable|string|max:50',
            'donor_address' => 'nullable|string',
            'amount' => 'required|numeric|min:1',
            'payment_method' => 'required|in:credit_card,bank_transfer,digital_wallet,other',
            'is_anonymous' => 'boolean',
            'message' => 'nullable|string',
            'tax_deductible' => 'boolean',
        ]);

        $validated['campaign_id'] = $campaign->id;
        $validated['currency'] = $campaign->currency;
        $validated['payment_status'] = 'pending';

        $donation = Donation::create($validated);

        // Process payment here (integrate with payment gateway)
        // This is a placeholder for payment processing
        $paymentResult = $this->processPayment($donation);

        if ($paymentResult['success']) {
            $donation->markAsCompleted($paymentResult['reference']);
            return redirect()->route('donations.success', $donation->id)
                ->with('success', 'Thank you for your donation!');
        }

        return back()->with('error', 'Payment failed. Please try again.');
    }

    public function success(Donation $donation)
    {
        return view('donations.success', compact('donation'));
    }

    public function index()
    {
        $donations = Donation::with('campaign')
            ->completed()
            ->latest()
            ->paginate(20);
            
        return view('donations.index', compact('donations'));
    }

    private function processPayment($donation)
    {
        // Integrate with your payment gateway here
        // This is a mock implementation
        return [
            'success' => true,
            'reference' => 'PAY-' . time() . '-' . $donation->id,
            'message' => 'Payment processed successfully'
        ];
    }
}