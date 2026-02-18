<?php
// app/Http/Controllers/Admin/DonorController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Donor;
use App\Models\CauseDonation;
use Illuminate\Http\Request;

class DonorController extends Controller
{
    public function index()
    {
        $pageTitle = 'All Donors';
        $donors = Donor::withCount('donations')
            ->orderBy('total_amount', 'desc')
            ->paginate(getPaginate());
            
        return view('admin.donors.index', compact('pageTitle', 'donors'));
    }

    public function details($id)
    {
        $pageTitle = 'Donor Details';
        $donor = Donor::withCount('donations')->findOrFail($id);
        
        $donations = CauseDonation::where('donor_id', $id)
            ->with('fundraiser')
            ->orderBy('created_at', 'desc')
            ->paginate(getPaginate());
            
        return view('admin.donors.details', compact('pageTitle', 'donor', 'donations'));
    }
}