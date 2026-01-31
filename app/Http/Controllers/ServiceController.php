<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\ServiceStory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Page; // Add this import

class ServiceController extends Controller
{
    public function index()
    {
        $pageTitle = 'Manage Services';
        $services = Service::withCount('campaigns')->latest()->get();
        
        // Get admin page sections if they exist
        $sections = Page::where('slug', 'admin-services')->first();
        
        return view('admin.services.index', compact('pageTitle', 'services', 'sections'));
    }

    public function create()
    {
        $pageTitle = 'Create New Service';
        return view('admin.services.create', compact('pageTitle'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255|unique:services,title',
            'mission' => 'required|string',
            'vision' => 'required|string',
            'description' => 'nullable|string',
            'impact_summary' => 'nullable|string',
            'featured_image' => 'nullable|image|max:2048',
            'gallery_images.*' => 'nullable|image|max:2048',
            'status' => 'required|in:active,inactive,draft',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
            'sort_order' => 'nullable|integer',
        ]);

        $validated['slug'] = Str::slug($request->title);
        $validated['created_by'] = auth()->id();

        if ($request->hasFile('featured_image')) {
            $validated['featured_image'] = $request->file('featured_image')->store('services', 'public');
        }

        // Handle gallery images
        if ($request->hasFile('gallery_images')) {
            $galleryPaths = [];
            foreach ($request->file('gallery_images') as $image) {
                $galleryPaths[] = $image->store('services/gallery', 'public');
            }
            $validated['gallery_images'] = json_encode($galleryPaths);
        }

        Service::create($validated);

        $notify[] = ['success', 'Service created successfully.'];
        return redirect()->route('admin.services.index')->withNotify($notify);
    }

    public function edit(Service $service)
    {
        $pageTitle = 'Edit Service';
        return view('admin.services.edit', compact('pageTitle', 'service'));
    }

    public function update(Request $request, Service $service)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255|unique:services,title,' . $service->id,
            'mission' => 'required|string',
            'vision' => 'required|string',
            'description' => 'nullable|string',
            'impact_summary' => 'nullable|string',
            'featured_image' => 'nullable|image|max:2048',
            'gallery_images.*' => 'nullable|image|max:2048',
            'status' => 'required|in:active,inactive,draft',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
            'sort_order' => 'nullable|integer',
        ]);

        $validated['slug'] = Str::slug($request->title);
        $validated['updated_by'] = auth()->id();

        if ($request->hasFile('featured_image')) {
            if ($service->featured_image) {
                \Storage::disk('public')->delete($service->featured_image);
            }
            $validated['featured_image'] = $request->file('featured_image')->store('services', 'public');
        }

        // Handle gallery images
        if ($request->hasFile('gallery_images')) {
            // Delete old gallery images
            if ($service->gallery_images) {
                $oldGallery = json_decode($service->gallery_images, true);
                foreach ($oldGallery as $oldImage) {
                    \Storage::disk('public')->delete($oldImage);
                }
            }
            
            $galleryPaths = [];
            foreach ($request->file('gallery_images') as $image) {
                $galleryPaths[] = $image->store('services/gallery', 'public');
            }
            $validated['gallery_images'] = json_encode($galleryPaths);
        }

        $service->update($validated);

        $notify[] = ['success', 'Service updated successfully.'];
        return redirect()->route('admin.services.index')->withNotify($notify);
    }

    public function destroy(Service $service)
    {
        // Delete featured image
        if ($service->featured_image) {
            \Storage::disk('public')->delete($service->featured_image);
        }
        
        // Delete gallery images
        if ($service->gallery_images) {
            $gallery = json_decode($service->gallery_images, true);
            foreach ($gallery as $image) {
                \Storage::disk('public')->delete($image);
            }
        }
        
        $service->delete();
        
        $notify[] = ['success', 'Service deleted successfully.'];
        return redirect()->route('admin.services.index')->withNotify($notify);
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
            'sort_order' => 'nullable|integer',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('service-stories', 'public');
        }

        $service->stories()->create($validated);

        $notify[] = ['success', 'Story added successfully.'];
        return back()->withNotify($notify);
    }
}