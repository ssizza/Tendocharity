<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Service;
use App\Models\CampaignUpdate;
use App\Models\CampaignMilestone;
use App\Models\CampaignFaq;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CampaignController extends Controller
{
    public function index()
    {
        $campaigns = Campaign::with('service')
            ->active()
            ->latest()
            ->paginate(12);
            
        $urgentCampaigns = Campaign::urgent()->active()->take(3)->get();
        
        return view('campaigns.index', compact('campaigns', 'urgentCampaigns'));
    }

    public function show($slug)
    {
        $campaign = Campaign::where('slug', $slug)
            ->with(['service', 'updates' => function($query) {
                $query->public()->latest();
            }, 'milestones' => function($query) {
                $query->orderBy('sort_order');
            }, 'faqs' => function($query) {
                $query->ordered();
            }, 'donations' => function($query) {
                $query->completed()->latest()->take(10);
            }])
            ->firstOrFail();
            
        $relatedCampaigns = Campaign::where('service_id', $campaign->service_id)
            ->where('id', '!=', $campaign->id)
            ->active()
            ->take(4)
            ->get();
            
        return view('campaigns.show', compact('campaign', 'relatedCampaigns'));
    }

    public function create()
    {
        $services = Service::active()->get();
        return view('campaigns.create', compact('services'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'service_id' => 'required|exists:services,id',
            'title' => 'required|string|max:255',
            'tagline' => 'nullable|string|max:500',
            'description' => 'required|string',
            'problem_statement' => 'nullable|string',
            'solution_statement' => 'nullable|string',
            'featured_image' => 'required|image|max:2048',
            'funding_goal' => 'required|numeric|min:0',
            'currency' => 'required|string|size:3',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'urgency_level' => 'required|in:normal,urgent,critical',
            'status' => 'required|in:draft,active,completed,cancelled',
            'location_country' => 'nullable|string|max:100',
            'location_region' => 'nullable|string|max:100',
            'beneficiaries_count' => 'nullable|integer|min:0',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
        ]);

        $validated['slug'] = $this->generateUniqueSlug($request->title);
        $validated['created_by'] = auth()->id();
        
        if ($request->hasFile('featured_image')) {
            $validated['featured_image'] = $request->file('featured_image')->store('campaigns', 'public');
        }

        $campaign = Campaign::create($validated);

        // Create initial milestone
        if ($request->filled('initial_milestone')) {
            $campaign->milestones()->create([
                'title' => 'Campaign Launch',
                'description' => 'Initial campaign launch and awareness',
                'target_amount' => $campaign->funding_goal * 0.1,
                'status' => 'pending',
                'sort_order' => 1
            ]);
        }

        return redirect()->route('campaigns.show', $campaign->slug)->with('success', 'Campaign created successfully.');
    }

    public function edit(Campaign $campaign)
    {
        $services = Service::active()->get();
        return view('campaigns.edit', compact('campaign', 'services'));
    }

    public function update(Request $request, Campaign $campaign)
    {
        $validated = $request->validate([
            'service_id' => 'required|exists:services,id',
            'title' => 'required|string|max:255',
            'tagline' => 'nullable|string|max:500',
            'description' => 'required|string',
            'problem_statement' => 'nullable|string',
            'solution_statement' => 'nullable|string',
            'featured_image' => 'nullable|image|max:2048',
            'funding_goal' => 'required|numeric|min:0',
            'currency' => 'required|string|size:3',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'urgency_level' => 'required|in:normal,urgent,critical',
            'status' => 'required|in:draft,active,completed,cancelled',
            'location_country' => 'nullable|string|max:100',
            'location_region' => 'nullable|string|max:100',
            'beneficiaries_count' => 'nullable|integer|min:0',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
        ]);

        $validated['updated_by'] = auth()->id();
        
        if ($request->hasFile('featured_image')) {
            if ($campaign->featured_image) {
                \Storage::disk('public')->delete($campaign->featured_image);
            }
            $validated['featured_image'] = $request->file('featured_image')->store('campaigns', 'public');
        }

        $campaign->update($validated);

        return redirect()->route('campaigns.show', $campaign->slug)->with('success', 'Campaign updated successfully.');
    }

    public function addUpdate(Request $request, Campaign $campaign)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|in:progress,milestone,general,emergency',
            'is_public' => 'boolean',
        ]);

        $validated['campaign_id'] = $campaign->id;
        $validated['created_by'] = auth()->id();

        CampaignUpdate::create($validated);
        
        // Increment updates count
        $campaign->increment('updates_count');

        return back()->with('success', 'Update added successfully.');
    }

    public function addMilestone(Request $request, Campaign $campaign)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'target_amount' => 'nullable|numeric|min:0',
            'completion_date' => 'nullable|date',
            'status' => 'required|in:pending,in_progress,completed,delayed',
        ]);

        $campaign->milestones()->create($validated);

        return back()->with('success', 'Milestone added successfully.');
    }

    public function addFaq(Request $request, Campaign $campaign)
    {
        $validated = $request->validate([
            'question' => 'required|string',
            'answer' => 'required|string',
        ]);

        $campaign->faqs()->create($validated);

        return back()->with('success', 'FAQ added successfully.');
    }

    private function generateUniqueSlug($title)
    {
        $slug = Str::slug($title);
        $count = Campaign::where('slug', $slug)->count();
        
        if ($count > 0) {
            $slug = $slug . '-' . ($count + 1);
        }
        
        return $slug;
    }
}