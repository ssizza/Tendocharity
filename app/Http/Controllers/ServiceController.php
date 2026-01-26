<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\ServiceStory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::active()->withCount('campaigns')->get();
        return view('services.index', compact('services'));
    }

    public function show($slug)
    {
        $service = Service::where('slug', $slug)
            ->with(['campaigns' => function($query) {
                $query->active()->latest();
            }, 'stories', 'caseStudies', 'testimonials'])
            ->firstOrFail();
            
        return view('services.show', compact('service'));
    }

    public function create()
    {
        return view('services.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'mission' => 'required|string',
            'vision' => 'required|string',
            'description' => 'nullable|string',
            'impact_summary' => 'nullable|string',
            'featured_image' => 'nullable|image|max:2048',
            'status' => 'required|in:active,inactive,draft',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
        ]);

        $validated['slug'] = Str::slug($request->title);
        $validated['created_by'] = auth()->id();

        if ($request->hasFile('featured_image')) {
            $validated['featured_image'] = $request->file('featured_image')->store('services', 'public');
        }

        Service::create($validated);

        return redirect()->route('services.index')->with('success', 'Service created successfully.');
    }

    public function edit(Service $service)
    {
        return view('services.edit', compact('service'));
    }

    public function update(Request $request, Service $service)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'mission' => 'required|string',
            'vision' => 'required|string',
            'description' => 'nullable|string',
            'impact_summary' => 'nullable|string',
            'featured_image' => 'nullable|image|max:2048',
            'status' => 'required|in:active,inactive,draft',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
        ]);

        $validated['slug'] = Str::slug($request->title);
        $validated['updated_by'] = auth()->id();

        if ($request->hasFile('featured_image')) {
            if ($service->featured_image) {
                \Storage::disk('public')->delete($service->featured_image);
            }
            $validated['featured_image'] = $request->file('featured_image')->store('services', 'public');
        }

        $service->update($validated);

        return redirect()->route('services.index')->with('success', 'Service updated successfully.');
    }

    public function destroy(Service $service)
    {
        if ($service->featured_image) {
            \Storage::disk('public')->delete($service->featured_image);
        }
        
        $service->delete();
        
        return redirect()->route('services.index')->with('success', 'Service deleted successfully.');
    }

    public function addStory(Request $request, Service $service)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|max:2048',
            'video_url' => 'nullable|url',
            'type' => 'required|in:story,case_study,testimonial',
            'author_name' => 'nullable|string|max:255',
            'author_position' => 'nullable|string|max:255',
            'featured' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('service-stories', 'public');
        }

        $service->stories()->create($validated);

        return back()->with('success', 'Story added successfully.');
    }
}