<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\ServiceStory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ServiceController extends Controller
{
    public function index()
    {
        $pageTitle = 'Manage Services';
        $services = Service::withCount(['campaigns', 'stories'])->latest()->paginate(getPaginate());
        return view('admin.services.index', compact('pageTitle', 'services'));
    }

    public function create()
    {
        $pageTitle = 'Create New Service';
        return view('admin.services.create', compact('pageTitle'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255|unique:services,title',
            'mission' => 'required|string',
            'vision' => 'required|string',
            'description' => 'nullable|string',
            'impact_summary' => 'nullable|string',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'gallery_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:active,inactive,draft',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $service = new Service();
        $service->title = $request->title;
        $service->slug = Str::slug($request->title);
        $service->mission = $request->mission;
        $service->vision = $request->vision;
        $service->description = $request->description;
        $service->impact_summary = $request->impact_summary;
        $service->status = $request->status;
        $service->meta_title = $request->meta_title;
        $service->meta_description = $request->meta_description;
        $service->meta_keywords = $request->meta_keywords;
        $service->sort_order = $request->sort_order ?? 0;
        $service->created_by = auth()->id();

        // Handle featured image upload - SIMPLIFIED VERSION
        if ($request->hasFile('featured_image')) {
            try {
                $file = $request->file('featured_image');
                $filename = uniqid() . time() . '.' . $file->getClientOriginalExtension();
                $location = 'assets/images/service';
                
                // Create directory if it doesn't exist
                if (!file_exists(public_path($location))) {
                    mkdir(public_path($location), 0755, true);
                }
                
                // Move the file
                $file->move(public_path($location), $filename);
                $service->featured_image = $location . '/' . $filename;
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Could not upload the featured image'];
                return back()->withNotify($notify)->withInput();
            }
        }

        // Handle gallery images - SIMPLIFIED VERSION
        if ($request->hasFile('gallery_images')) {
            $galleryImages = [];
            $galleryPath = 'assets/images/service/gallery';
            
            // Create directory if it doesn't exist
            if (!file_exists(public_path($galleryPath))) {
                mkdir(public_path($galleryPath), 0755, true);
            }
            
            foreach ($request->file('gallery_images') as $image) {
                try {
                    $filename = uniqid() . time() . '.' . $image->getClientOriginalExtension();
                    $image->move(public_path($galleryPath), $filename);
                    $galleryImages[] = $galleryPath . '/' . $filename;
                } catch (\Exception $exp) {
                    $notify[] = ['error', 'Could not upload gallery image: ' . $image->getClientOriginalName()];
                    return back()->withNotify($notify)->withInput();
                }
            }
            $service->gallery_images = json_encode($galleryImages);
        }

        $service->save();

        $notify[] = ['success', 'Service created successfully'];
        return redirect()->route('admin.services.index')->withNotify($notify);
    }


    public function edit(Service $service)
    {
        $pageTitle = 'Edit Service';
        return view('admin.services.edit', compact('pageTitle', 'service'));
    }

    public function update(Request $request, Service $service)
    {
        $request->validate([
            'title' => 'required|string|max:255|unique:services,title,' . $service->id,
            'mission' => 'required|string',
            'vision' => 'required|string',
            'description' => 'nullable|string',
            'impact_summary' => 'nullable|string',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'gallery_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:active,inactive,draft',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $oldFeaturedImage = $service->featured_image;
        $oldGalleryImages = $service->gallery_images ? json_decode($service->gallery_images) : [];

        $service->title = $request->title;
        $service->slug = Str::slug($request->title);
        $service->mission = $request->mission;
        $service->vision = $request->vision;
        $service->description = $request->description;
        $service->impact_summary = $request->impact_summary;
        $service->status = $request->status;
        $service->meta_title = $request->meta_title;
        $service->meta_description = $request->meta_description;
        $service->meta_keywords = $request->meta_keywords;
        $service->sort_order = $request->sort_order ?? 0;
        $service->updated_by = auth()->id();

        // Handle featured image upload - SIMPLIFIED VERSION
        if ($request->hasFile('featured_image')) {
            try {
                // Delete old image if exists
                if ($oldFeaturedImage && file_exists(public_path($oldFeaturedImage))) {
                    @unlink(public_path($oldFeaturedImage));
                }
                
                $file = $request->file('featured_image');
                $filename = uniqid() . time() . '.' . $file->getClientOriginalExtension();
                $location = 'assets/images/service';
                
                // Create directory if it doesn't exist
                if (!file_exists(public_path($location))) {
                    mkdir(public_path($location), 0755, true);
                }
                
                // Move the file
                $file->move(public_path($location), $filename);
                $service->featured_image = $location . '/' . $filename;
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Could not upload the featured image'];
                return back()->withNotify($notify)->withInput();
            }
        }

        // Handle gallery images - SIMPLIFIED VERSION
        if ($request->hasFile('gallery_images')) {
            $galleryPath = 'assets/images/service/gallery';
            
            // Create directory if it doesn't exist
            if (!file_exists(public_path($galleryPath))) {
                mkdir(public_path($galleryPath), 0755, true);
            }
            
            $newGalleryImages = $oldGalleryImages;
            
            foreach ($request->file('gallery_images') as $image) {
                try {
                    $filename = uniqid() . time() . '.' . $image->getClientOriginalExtension();
                    $image->move(public_path($galleryPath), $filename);
                    $newGalleryImages[] = $galleryPath . '/' . $filename;
                } catch (\Exception $exp) {
                    $notify[] = ['error', 'Could not upload gallery image: ' . $image->getClientOriginalName()];
                    return back()->withNotify($notify)->withInput();
                }
            }
            $service->gallery_images = json_encode($newGalleryImages);
        }

        $service->save();

        $notify[] = ['success', 'Service updated successfully'];
        return redirect()->route('admin.services.index')->withNotify($notify);
    }

    public function toggleStatus(Request $request, Service $service)
    {
        $request->validate([
            'status' => 'required|in:active,inactive,draft'
        ]);

        $service->status = $request->status;
        $service->save();

        $notify[] = ['success', 'Service status updated successfully'];
        return back()->withNotify($notify);
    }

    public function stories()
    {
        $pageTitle = 'Service Stories & Testimonials';
        $stories = ServiceStory::with('service')->latest()->paginate(getPaginate());
        return view('admin.services.stories', compact('pageTitle', 'stories'));
    }

    public function createStory()
    {
        $pageTitle = 'Create New Story';
        $services = Service::active()->get();
        return view('admin.services.story_form', compact('pageTitle', 'services'));
    }

    public function storeStory(Request $request)
    {
        $request->validate([
            'service_id' => 'required|exists:services,id',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'video_url' => 'nullable|url',
            'type' => 'required|in:story,case_study,testimonial',
            'author_name' => 'nullable|string|max:255',
            'author_position' => 'nullable|string|max:255',
            'featured' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $story = new ServiceStory();
        $story->service_id = $request->service_id;
        $story->title = $request->title;
        $story->content = $request->content;
        $story->video_url = $request->video_url;
        $story->type = $request->type;
        $story->author_name = $request->author_name;
        $story->author_position = $request->author_position;
        $story->featured = $request->featured ?? false;
        $story->sort_order = $request->sort_order ?? 0;

        // Handle image upload - SIMPLIFIED VERSION
        if ($request->hasFile('image')) {
            try {
                $file = $request->file('image');
                $filename = uniqid() . time() . '.' . $file->getClientOriginalExtension();
                $location = 'assets/images/service/story';
                
                // Create directory if it doesn't exist
                if (!file_exists(public_path($location))) {
                    mkdir(public_path($location), 0755, true);
                }
                
                // Move the file
                $file->move(public_path($location), $filename);
                $story->image = $location . '/' . $filename;
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Could not upload the image'];
                return back()->withNotify($notify)->withInput();
            }
        }

        $story->save();

        $notify[] = ['success', 'Story created successfully'];
        return redirect()->route('admin.services.stories')->withNotify($notify);
    }

    public function updateStory(Request $request, ServiceStory $story)
    {
        $request->validate([
            'service_id' => 'required|exists:services,id',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'video_url' => 'nullable|url',
            'type' => 'required|in:story,case_study,testimonial',
            'author_name' => 'nullable|string|max:255',
            'author_position' => 'nullable|string|max:255',
            'featured' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $oldImage = $story->image;

        $story->service_id = $request->service_id;
        $story->title = $request->title;
        $story->content = $request->content;
        $story->video_url = $request->video_url;
        $story->type = $request->type;
        $story->author_name = $request->author_name;
        $story->author_position = $request->author_position;
        $story->featured = $request->featured ?? false;
        $story->sort_order = $request->sort_order ?? 0;

        // Handle image upload - SIMPLIFIED VERSION
        if ($request->hasFile('image')) {
            try {
                // Delete old image if exists
                if ($oldImage && file_exists(public_path($oldImage))) {
                    @unlink(public_path($oldImage));
                }
                
                $file = $request->file('image');
                $filename = uniqid() . time() . '.' . $file->getClientOriginalExtension();
                $location = 'assets/images/service/story';
                
                // Create directory if it doesn't exist
                if (!file_exists(public_path($location))) {
                    mkdir(public_path($location), 0755, true);
                }
                
                // Move the file
                $file->move(public_path($location), $filename);
                $story->image = $location . '/' . $filename;
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Could not upload the image'];
                return back()->withNotify($notify)->withInput();
            }
        }

        $story->save();

        $notify[] = ['success', 'Story updated successfully'];
        return redirect()->route('admin.services.stories')->withNotify($notify);
    }

    public function editStory(ServiceStory $story)
    {
        $pageTitle = 'Edit Story';
        $services = Service::active()->get();
        return view('admin.services.story_form', compact('pageTitle', 'services', 'story'));
    }

    public function destroy(Service $service)
    {
        // Delete featured image
        if ($service->featured_image && file_exists(public_path($service->featured_image))) {
            @unlink(public_path($service->featured_image));
        }

        // Delete gallery images
        if ($service->gallery_images) {
            $galleryImages = json_decode($service->gallery_images);
            foreach ($galleryImages as $image) {
                if (file_exists(public_path($image))) {
                    @unlink(public_path($image));
                }
            }
        }

        // Delete related stories (and their images)
        foreach ($service->stories as $story) {
            if ($story->image && file_exists(public_path($story->image))) {
                @unlink(public_path($story->image));
            }
            $story->delete();
        }
        
        // Delete the service
        $service->delete();

        $notify[] = ['success', 'Service deleted successfully'];
        return back()->withNotify($notify);
    }

    public function destroyStory(ServiceStory $story)
    {
        // Delete image
        if ($story->image && file_exists(public_path($story->image))) {
            @unlink(public_path($story->image));
        }

        $story->delete();

        $notify[] = ['success', 'Story deleted successfully'];
        return back()->withNotify($notify);
    }
    public function categories(Service $service)
    {
        $pageTitle = "Categories for {$service->title}";
        $categories = $service->categories()->withCount('fundraisers')->latest()->paginate(getPaginate());
        return view('admin.services.categories', compact('pageTitle', 'service', 'categories'));
    }

}