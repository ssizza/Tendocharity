<?php
namespace App\Http\Controllers\Admin\Fundraisers;

use App\Http\Controllers\Controller;
use App\Models\Fundraiser;
use App\Models\Category;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FundraiserController extends Controller
{
    public function index()
    {
        $pageTitle = 'All Causes';
        $fundraisers = Fundraiser::with(['service', 'category'])
            ->latest()
            ->paginate(getPaginate());
        
        return view('admin.fundraisers.index', compact('pageTitle', 'fundraisers'));
    }

    public function pending()
    {
        $pageTitle = 'Pending Causes';
        $fundraisers = Fundraiser::where('status', 'pending')
            ->with(['service', 'category', 'createdBy'])
            ->latest()
            ->paginate(getPaginate());
        
        return view('admin.fundraisers.pending', compact('pageTitle', 'fundraisers'));
    }

    public function create()
    {
        $pageTitle = 'Create Cause';
        $services = Service::active()->get();
        $categories = Category::active()->get();
        
        return view('admin.fundraisers.create', compact('pageTitle', 'services', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'service_id' => 'nullable|exists:services,id',
            'category_id' => 'nullable|exists:categories,id',
            'title' => 'required|string|max:255|unique:fundraisers,title',
            'tagline' => 'nullable|string|max:500',
            'short_description' => 'required|string|max:500',
            'description' => 'required|string',
            'problem_statement' => 'nullable|string',
            'solution_statement' => 'nullable|string',
            'featured_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'gallery_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'target_amount' => 'required|numeric|min:0',
            'currency' => 'required|string|size:3',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'urgency_level' => 'required|in:normal,urgent,critical',
            'video_url' => 'nullable|url',
            'location' => 'nullable|string|max:255',
            'location_country' => 'nullable|string|max:100',
            'location_region' => 'nullable|string|max:100',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'is_featured' => 'boolean',
            'priority' => 'nullable|integer|min:0',
            'status' => 'required|in:draft,pending,active',
            
            // Cause-specific fields
            'project_leader' => 'required|string|max:255',
            'organization_name' => 'nullable|string|max:255',
            'organization_type' => 'nullable|string|max:100',
            'beneficiaries' => 'nullable|string',
            'total_beneficiaries_target' => 'nullable|integer|min:0',
            'risks_challenges' => 'nullable|string',
            'sustainability_plan' => 'nullable|string',
            'project_scope' => 'nullable|string',
            'timeline' => 'nullable|array',
            'timeline.*.phase' => 'required_with:timeline|string|max:255',
            'timeline.*.duration' => 'nullable|string|max:100',
            'timeline.*.description' => 'nullable|string',
            
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
        ]);

        $fundraiser = new Fundraiser();
        
        // Basic information
        $fundraiser->service_id = $request->service_id;
        $fundraiser->category_id = $request->category_id;
        $fundraiser->title = $request->title;
        $fundraiser->tagline = $request->tagline;
        $fundraiser->short_description = $request->short_description;
        $fundraiser->description = $request->description;
        $fundraiser->problem_statement = $request->problem_statement;
        $fundraiser->solution_statement = $request->solution_statement;
        
        // Funding information
        $fundraiser->target_amount = $request->target_amount;
        $fundraiser->currency = $request->currency;
        $fundraiser->start_date = $request->start_date;
        $fundraiser->end_date = $request->end_date;
        $fundraiser->urgency_level = $request->urgency_level;
        
        // Location information
        $fundraiser->location = $request->location;
        $fundraiser->location_country = $request->location_country;
        $fundraiser->location_region = $request->location_region;
        $fundraiser->latitude = $request->latitude;
        $fundraiser->longitude = $request->longitude;
        
        // Cause details
        $fundraiser->project_leader = $request->project_leader;
        $fundraiser->organization_name = $request->organization_name;
        $fundraiser->organization_type = $request->organization_type;
        $fundraiser->beneficiaries = $request->beneficiaries;
        $fundraiser->total_beneficiaries_target = $request->total_beneficiaries_target;
        $fundraiser->risks_challenges = $request->risks_challenges;
        $fundraiser->sustainability_plan = $request->sustainability_plan;
        $fundraiser->project_scope = $request->project_scope;
        $fundraiser->timeline = $request->timeline ? json_encode($request->timeline) : null;
        
        // Settings
        $fundraiser->is_featured = $request->is_featured ?? false;
        $fundraiser->priority = $request->priority ?? 0;
        $fundraiser->video_url = $request->video_url;
        $fundraiser->status = $request->status;
        
        // SEO
        $fundraiser->meta_title = $request->meta_title;
        $fundraiser->meta_description = $request->meta_description;
        $fundraiser->meta_keywords = $request->meta_keywords;
        
        $fundraiser->created_by = auth()->id();

        // Handle featured image upload
        if ($request->hasFile('featured_image')) {
            try {
                $file = $request->file('featured_image');
                $filename = uniqid() . time() . '.' . $file->getClientOriginalExtension();
                $location = 'assets/images/fundraisers';
                
                // Create directory if it doesn't exist
                $path = public_path($location);
                if (!file_exists($path)) {
                    mkdir($path, 0755, true);
                }
                
                $file->move($path, $filename);
                $fundraiser->featured_image = $location . '/' . $filename;
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Could not upload the featured image: ' . $exp->getMessage()];
                return back()->withNotify($notify)->withInput();
            }
        }

        // Handle gallery images
        if ($request->hasFile('gallery_images')) {
            $galleryImages = [];
            $galleryPath = 'assets/images/fundraisers/gallery';
            $galleryFullPath = public_path($galleryPath);
            
            // Create directory if it doesn't exist
            if (!file_exists($galleryFullPath)) {
                mkdir($galleryFullPath, 0755, true);
            }
            
            foreach ($request->file('gallery_images') as $image) {
                try {
                    $filename = uniqid() . time() . '.' . $image->getClientOriginalExtension();
                    $image->move($galleryFullPath, $filename);
                    $galleryImages[] = $galleryPath . '/' . $filename;
                } catch (\Exception $exp) {
                    $notify[] = ['error', 'Could not upload gallery image: ' . $exp->getMessage()];
                    return back()->withNotify($notify)->withInput();
                }
            }
            $fundraiser->gallery_images = json_encode($galleryImages);
        }

        $fundraiser->save();

        $notify[] = ['success', 'Cause created successfully'];
        
        if ($request->status === 'pending') {
            return redirect()->route('admin.fundraisers.pending')->withNotify($notify);
        }
        
        return redirect()->route('admin.fundraisers.index')->withNotify($notify);
    }

    public function edit(Fundraiser $fundraiser)
    {
        $pageTitle = 'Edit Cause';
        $services = Service::active()->get();
        $categories = Category::active()->get();
        
        return view('admin.fundraisers.edit', compact('pageTitle', 'fundraiser', 'services', 'categories'));
    }

    public function update(Request $request, Fundraiser $fundraiser)
    {
        $request->validate([
            'service_id' => 'nullable|exists:services,id',
            'category_id' => 'nullable|exists:categories,id',
            'title' => 'required|string|max:255|unique:fundraisers,title,' . $fundraiser->id,
            'tagline' => 'nullable|string|max:500',
            'short_description' => 'required|string|max:500',
            'description' => 'required|string',
            'problem_statement' => 'nullable|string',
            'solution_statement' => 'nullable|string',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'gallery_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'target_amount' => 'required|numeric|min:0',
            'currency' => 'required|string|size:3',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'urgency_level' => 'required|in:normal,urgent,critical',
            'video_url' => 'nullable|url',
            'location' => 'nullable|string|max:255',
            'location_country' => 'nullable|string|max:100',
            'location_region' => 'nullable|string|max:100',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'is_featured' => 'boolean',
            'priority' => 'nullable|integer|min:0',
            'status' => 'required|in:draft,pending,active,completed,cancelled,rejected',
            
            // Cause-specific fields
            'project_leader' => 'required|string|max:255',
            'organization_name' => 'nullable|string|max:255',
            'organization_type' => 'nullable|string|max:100',
            'beneficiaries' => 'nullable|string',
            'total_beneficiaries_target' => 'nullable|integer|min:0',
            'risks_challenges' => 'nullable|string',
            'sustainability_plan' => 'nullable|string',
            'project_scope' => 'nullable|string',
            'timeline' => 'nullable|array',
            'timeline.*.phase' => 'required_with:timeline|string|max:255',
            'timeline.*.duration' => 'nullable|string|max:100',
            'timeline.*.description' => 'nullable|string',
            
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
        ]);

        $oldFeaturedImage = $fundraiser->featured_image;
        $oldGalleryImages = $fundraiser->gallery_images ? json_decode($fundraiser->gallery_images) : [];

        // Update basic information
        $fundraiser->service_id = $request->service_id;
        $fundraiser->category_id = $request->category_id;
        $fundraiser->title = $request->title;
        $fundraiser->tagline = $request->tagline;
        $fundraiser->short_description = $request->short_description;
        $fundraiser->description = $request->description;
        $fundraiser->problem_statement = $request->problem_statement;
        $fundraiser->solution_statement = $request->solution_statement;
        
        // Update funding information
        $fundraiser->target_amount = $request->target_amount;
        $fundraiser->currency = $request->currency;
        $fundraiser->start_date = $request->start_date;
        $fundraiser->end_date = $request->end_date;
        $fundraiser->urgency_level = $request->urgency_level;
        
        // Update location information
        $fundraiser->location = $request->location;
        $fundraiser->location_country = $request->location_country;
        $fundraiser->location_region = $request->location_region;
        $fundraiser->latitude = $request->latitude;
        $fundraiser->longitude = $request->longitude;
        
        // Update cause details
        $fundraiser->project_leader = $request->project_leader;
        $fundraiser->organization_name = $request->organization_name;
        $fundraiser->organization_type = $request->organization_type;
        $fundraiser->beneficiaries = $request->beneficiaries;
        $fundraiser->total_beneficiaries_target = $request->total_beneficiaries_target;
        $fundraiser->risks_challenges = $request->risks_challenges;
        $fundraiser->sustainability_plan = $request->sustainability_plan;
        $fundraiser->project_scope = $request->project_scope;
        $fundraiser->timeline = $request->timeline ? json_encode($request->timeline) : null;
        
        // Update settings
        $fundraiser->is_featured = $request->is_featured ?? false;
        $fundraiser->priority = $request->priority ?? 0;
        $fundraiser->video_url = $request->video_url;
        $fundraiser->status = $request->status;
        
        // Update SEO
        $fundraiser->meta_title = $request->meta_title;
        $fundraiser->meta_description = $request->meta_description;
        $fundraiser->meta_keywords = $request->meta_keywords;
        
        $fundraiser->updated_by = auth()->id();

        // Handle featured image upload
        if ($request->hasFile('featured_image')) {
            try {
                // Delete old image if exists
                if ($oldFeaturedImage && file_exists(public_path($oldFeaturedImage))) {
                    @unlink(public_path($oldFeaturedImage));
                }
                
                $file = $request->file('featured_image');
                $filename = uniqid() . time() . '.' . $file->getClientOriginalExtension();
                $location = 'assets/images/fundraisers';
                
                // Create directory if it doesn't exist
                $path = public_path($location);
                if (!file_exists($path)) {
                    mkdir($path, 0755, true);
                }
                
                $file->move($path, $filename);
                $fundraiser->featured_image = $location . '/' . $filename;
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Could not upload the featured image: ' . $exp->getMessage()];
                return back()->withNotify($notify)->withInput();
            }
        }

        // Handle gallery images
        if ($request->hasFile('gallery_images')) {
            $galleryPath = 'assets/images/fundraisers/gallery';
            $galleryFullPath = public_path($galleryPath);
            
            // Create directory if it doesn't exist
            if (!file_exists($galleryFullPath)) {
                mkdir($galleryFullPath, 0755, true);
            }
            
            $newGalleryImages = $oldGalleryImages;
            
            foreach ($request->file('gallery_images') as $image) {
                try {
                    $filename = uniqid() . time() . '.' . $image->getClientOriginalExtension();
                    $image->move($galleryFullPath, $filename);
                    $newGalleryImages[] = $galleryPath . '/' . $filename;
                } catch (\Exception $exp) {
                    $notify[] = ['error', 'Could not upload gallery image: ' . $exp->getMessage()];
                    return back()->withNotify($notify)->withInput();
                }
            }
            $fundraiser->gallery_images = json_encode($newGalleryImages);
        }

        $fundraiser->save();

        $notify[] = ['success', 'Cause updated successfully'];
        return redirect()->route('admin.fundraisers.index')->withNotify($notify);
    }

    public function approve(Fundraiser $fundraiser)
    {
        $fundraiser->status = 'active';
        $fundraiser->approved_by = auth()->id();
        $fundraiser->approved_at = now();
        $fundraiser->save();

        $notify[] = ['success', 'Cause approved successfully'];
        return back()->withNotify($notify);
    }

    public function reject(Fundraiser $fundraiser)
    {
        $fundraiser->status = 'rejected';
        $fundraiser->save();

        $notify[] = ['success', 'Cause rejected'];
        return back()->withNotify($notify);
    }

    public function toggleFeatured(Request $request, Fundraiser $fundraiser)
    {
        $fundraiser->is_featured = !$fundraiser->is_featured;
        $fundraiser->save();

        $status = $fundraiser->is_featured ? 'featured' : 'unfeatured';
        $notify[] = ['success', "Cause {$status} successfully"];
        return back()->withNotify($notify);
    }

    public function destroy(Fundraiser $fundraiser)
    {
        // Delete featured image
        if ($fundraiser->featured_image && file_exists(public_path($fundraiser->featured_image))) {
            @unlink(public_path($fundraiser->featured_image));
        }

        // Delete gallery images
        if ($fundraiser->gallery_images) {
            $galleryImages = json_decode($fundraiser->gallery_images);
            foreach ($galleryImages as $image) {
                if (file_exists(public_path($image))) {
                    @unlink(public_path($image));
                }
            }
        }

        // Comment out related data deletion for now
        // $fundraiser->faqs()->delete();
        // $fundraiser->milestones()->delete();
        // $fundraiser->updates()->delete();
        // $fundraiser->donations()->delete();

        $fundraiser->delete();

        $notify[] = ['success', 'Cause deleted successfully'];
        return back()->withNotify($notify);
    }

    public function updateStatus(Request $request, Fundraiser $fundraiser)
    {
        $request->validate([
            'status' => 'required|in:draft,pending,active,completed,cancelled,rejected'
        ]);

        $fundraiser->status = $request->status;
        
        if ($request->status === 'active') {
            $fundraiser->approved_by = auth()->id();
            $fundraiser->approved_at = now();
        }
        
        $fundraiser->save();

        $notify[] = ['success', 'Cause status updated successfully'];
        return back()->withNotify($notify);
    }
}