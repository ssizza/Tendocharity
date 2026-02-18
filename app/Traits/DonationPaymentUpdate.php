<?php
// app/Traits/DonationPaymentUpdate.php

namespace App\Traits;

use App\Models\CauseDonation;
use App\Models\Fundraiser;
use App\Models\Donor;
use App\Models\AdminNotification;

trait DonationPaymentUpdate
{
    public static function donationDataUpdate($donation)
    {
        if ($donation->payment_status == 'pending') {
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

            // Create admin notification
            $adminNotification = new AdminNotification();
            $adminNotification->title = 'New donation completed - ' . $donation->donor_name . ' (' . $donation->payment_reference . ')';
            $adminNotification->click_url = route('admin.donations.details', $donation->id);
            $adminNotification->save();

            // Send receipt email
            // self::sendDonationReceipt($donation);
        }
    }
}