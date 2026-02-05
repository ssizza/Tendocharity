<?php

namespace App\Http\Controllers;

use App\Models\Fundraiser;
use App\Models\Category;
use App\Models\CauseDonation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FundraiserController extends Controller
{
    // Get featured fundraiser (limit 1)
    public function getFeaturedFundraiser()
    {
        $featured = Fundraiser::with(['category', 'service'])
            ->where('status', 'active')
            ->where('is_featured', 1)
            ->orderBy('priority', 'desc')
            ->orderBy('created_at', 'desc')
            ->first();
            
        return $featured;
    }

    // Get all active fundraisers with pagination
    public function getAllFundraisers(Request $request)
    {
        $query = Fundraiser::with(['category', 'service'])
            ->where('status', 'active');
            
        // Filter by category if provided
        if ($request->has('category')) {
            $query->whereHas('category', function($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }
        
        // Filter by urgency level
        if ($request->has('urgency')) {
            $query->where('urgency_level', $request->urgency);
        }
        
        // Search by title or tagline
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('tagline', 'like', "%{$search}%")
                  ->orWhere('short_description', 'like', "%{$search}%");
            });
        }
        
        // Filter by date range
        if ($request->has('date_range')) {
            switch ($request->date_range) {
                case 'upcoming':
                    $query->where('start_date', '>', now());
                    break;
                case 'ongoing':
                    $query->where('start_date', '<=', now())
                          ->where('end_date', '>=', now());
                    break;
                case 'ending_soon':
                    $query->where('end_date', '<=', now()->addDays(7))
                          ->where('end_date', '>=', now());
                    break;
            }
        }
        
        // Sort options
        $sort = $request->get('sort', 'created_at');
        $order = $request->get('order', 'desc');
        
        $validSorts = ['created_at', 'raised_amount', 'target_amount', 'start_date', 'end_date', 'priority'];
        if (in_array($sort, $validSorts)) {
            $query->orderBy($sort, $order);
        }
        
        // Get paginated results
        $perPage = $request->get('per_page', 12);
        $fundraisers = $query->paginate($perPage);
        
        return $fundraisers;
    }

    // Get fundraiser details by slug
    public function getFundraiserDetails($slug)
    {
        $fundraiser = Fundraiser::with(['category', 'service'])
            ->where('slug', $slug)
            ->where('status', 'active')
            ->firstOrFail();
            
        // Get recent donations for this fundraiser
        $donations = CauseDonation::where('fundraiser_id', $fundraiser->id)
            ->where('payment_status', 'completed')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
            
        // Get similar fundraisers (same category)
        $similar = Fundraiser::with(['category'])
            ->where('category_id', $fundraiser->category_id)
            ->where('id', '!=', $fundraiser->id)
            ->where('status', 'active')
            ->limit(4)
            ->get();
            
        return compact('fundraiser', 'donations', 'similar');
    }

    // Get donation statistics for a fundraiser
    public function getDonationStats($fundraiserId)
    {
        return [
            'total_donations' => CauseDonation::where('fundraiser_id', $fundraiserId)
                ->where('payment_status', 'completed')
                ->count(),
            'total_amount' => CauseDonation::where('fundraiser_id', $fundraiserId)
                ->where('payment_status', 'completed')
                ->sum('amount'),
            'recent_donations' => CauseDonation::where('fundraiser_id', $fundraiserId)
                ->where('payment_status', 'completed')
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get()
        ];
    }

    // Main index page
    public function index(Request $request)
    {
        $categories = Category::where('status', 'active')->get();
        $fundraisers = $this->getAllFundraisers($request);
        
        // Get featured fundraiser
        $featuredFundraiser = $this->getFeaturedFundraiser();
        
        return view('fundraisers.index', compact('fundraisers', 'categories', 'featuredFundraiser'));
    }

    // Show fundraiser details
    public function show($slug)
    {
        $data = $this->getFundraiserDetails($slug);
        $data['donationStats'] = $this->getDonationStats($data['fundraiser']->id);
        
        return view('fundraisers.show', $data);
    }

    // Create a donation
    public function createDonation(Request $request, $fundraiserId)
    {
        $request->validate([
            'donor_name' => 'required|string|max:255',
            'donor_email' => 'required|email|max:255',
            'amount' => 'required|numeric|min:1',
            'message' => 'nullable|string|max:1000',
            'is_anonymous' => 'boolean',
            'tax_deductible' => 'boolean'
        ]);
        
        $fundraiser = Fundraiser::findOrFail($fundraiserId);
        
        $donation = CauseDonation::create([
            'fundraiser_id' => $fundraiser->id,
            'donor_name' => $request->donor_name,
            'donor_email' => $request->donor_email,
            'donor_phone' => $request->donor_phone,
            'donor_address' => $request->donor_address,
            'amount' => $request->amount,
            'currency' => $fundraiser->currency,
            'payment_method' => 'credit_card', // Default, can be updated after payment
            'payment_status' => 'pending',
            'payment_reference' => null,
            'is_anonymous' => $request->is_anonymous ?? false,
            'message' => $request->message,
            'tax_deductible' => $request->tax_deductible ?? false,
            'receipt_sent' => false,
            'metadata' => json_encode([
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ])
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Donation created successfully',
            'donation' => $donation
        ]);
    }
}