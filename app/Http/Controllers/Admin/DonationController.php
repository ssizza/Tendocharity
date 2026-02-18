<?php
// app/Http/Controllers/Admin/DonationController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CauseDonation;
use App\Models\Fundraiser;
use App\Models\Donor;
use App\Models\AdminNotification;
use Illuminate\Http\Request;
use App\Constants\Status;

class DonationController extends Controller
{
    public function index()
    {
        $pageTitle = 'All Donations';
        $donations = CauseDonation::with(['fundraiser', 'donor'])
            ->orderBy('created_at', 'desc')
            ->paginate(getPaginate());
            
        return view('admin.donations.index', compact('pageTitle', 'donations'));
    }

    public function pending()
    {
        $pageTitle = 'Pending Donations';
        $donations = CauseDonation::with(['fundraiser', 'donor'])
            ->where('payment_status', 'pending')
            ->orderBy('created_at', 'desc')
            ->paginate(getPaginate());
            
        return view('admin.donations.index', compact('pageTitle', 'donations'));
    }

    public function completed()
    {
        $pageTitle = 'Completed Donations';
        $donations = CauseDonation::with(['fundraiser', 'donor'])
            ->where('payment_status', 'completed')
            ->orderBy('created_at', 'desc')
            ->paginate(getPaginate());
            
        return view('admin.donations.index', compact('pageTitle', 'donations'));
    }

    public function failed()
    {
        $pageTitle = 'Failed Donations';
        $donations = CauseDonation::with(['fundraiser', 'donor'])
            ->whereIn('payment_status', ['failed', 'refunded'])
            ->orderBy('created_at', 'desc')
            ->paginate(getPaginate());
            
        return view('admin.donations.index', compact('pageTitle', 'donations'));
    }

    public function details($id)
    {
        $pageTitle = 'Donation Details';
        $donation = CauseDonation::with(['fundraiser', 'donor'])
            ->findOrFail($id);
            
        $metadata = json_decode($donation->metadata, true);
            
        return view('admin.donations.details', compact('pageTitle', 'donation', 'metadata'));
    }

    public function approve($id)
    {
        $donation = CauseDonation::with('fundraiser')->findOrFail($id);
        
        if ($donation->payment_status != 'pending') {
            $notify[] = ['error', 'This donation is not pending verification'];
            return back()->withNotify($notify);
        }

        // Update donation status
        $donation->payment_status = 'completed';
        $donation->save();

        // Update fundraiser stats
        $fundraiser = Fundraiser::find($donation->fundraiser_id);
        if ($fundraiser) {
            $fundraiser->raised_amount += $donation->amount;
            $fundraiser->progress_percentage = ($fundraiser->raised_amount / $fundraiser->target_amount) * 100;
            $fundraiser->donors_count = CauseDonation::where('fundraiser_id', $fundraiser->id)
                ->where('payment_status', 'completed')
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

        // Create admin notification for tracking
        $adminNotification = new AdminNotification();
        $adminNotification->title = 'Donation approved - ' . $donation->donor_name . ' (' . $donation->payment_reference . ')';
        $adminNotification->click_url = route('admin.donations.details', $donation->id);
        $adminNotification->save();

        // TODO: Send email notification to donor using notification template
        // $this->sendDonationApprovedEmail($donation);

        $notify[] = ['success', 'Donation approved successfully'];
        return back()->withNotify($notify);
    }

    public function reject(Request $request, $id)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500'
        ]);

        $donation = CauseDonation::findOrFail($id);
        
        if ($donation->payment_status != 'pending') {
            $notify[] = ['error', 'This donation is not pending verification'];
            return back()->withNotify($notify);
        }

        // Update donation status
        $donation->payment_status = 'failed';
        
        // Add rejection reason to metadata
        $metadata = json_decode($donation->metadata, true) ?: [];
        $metadata['rejection'] = [
            'reason' => $request->rejection_reason,
            'rejected_by' => auth()->guard('admin')->user()->name,
            'rejected_at' => now()->toDateTimeString()
        ];
        $donation->metadata = json_encode($metadata);
        $donation->save();

        // Create admin notification
        $adminNotification = new AdminNotification();
        $adminNotification->title = 'Donation rejected - ' . $donation->donor_name . ' (' . $donation->payment_reference . ')';
        $adminNotification->click_url = route('admin.donations.details', $donation->id);
        $adminNotification->save();

        // TODO: Send rejection email to donor with reason

        $notify[] = ['warning', 'Donation rejected'];
        return back()->withNotify($notify);
    }

    public function destroy($id)
    {
        $donation = CauseDonation::findOrFail($id);
        $donation->delete();

        $notify[] = ['success', 'Donation deleted successfully'];
        return back()->withNotify($notify);
    }
}