<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\TeamCategory;
use App\Rules\FileTypeValidate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TeamController extends Controller
{
    /**
     * Display a listing of team members
     */
    public function index(Request $request)
    {
        $pageTitle = 'All Team Members';
        
        $members = Member::with('category')
            ->when($request->search, function($query) use ($request) {
                $query->where(function($q) use ($request) {
                    $q->where('name', 'like', "%{$request->search}%")
                      ->orWhere('email', 'like', "%{$request->search}%")
                      ->orWhere('position', 'like', "%{$request->search}%");
                });
            })
            ->when($request->category, function($query) use ($request) {
                $query->where('category_id', $request->category);
            })
            ->when($request->status, function($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(getPaginate());

        $categories = TeamCategory::orderBy('name')->get();
        $statuses = ['active' => 'Active', 'inactive' => 'Inactive'];
        
        return view('admin.team.index', compact('pageTitle', 'members', 'categories', 'statuses'));
    }

    /**
     * Show form to create new team member
     */
    public function create()
    {
        $pageTitle = 'Add New Team Member';
        $categories = TeamCategory::orderBy('name')->get();
        
        return view('admin.team.create', compact('pageTitle', 'categories'));
    }

    /**
     * Store a newly created team member
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'category_id' => 'required|exists:team_categories,id',
            'position' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
            'status' => 'required|in:active,inactive',
            'image' => ['nullable', 'image', new FileTypeValidate(['jpg', 'jpeg', 'png', 'gif'])],
            
            // Social media validation
            'social_facebook' => 'nullable|url|max:255',
            'social_twitter' => 'nullable|url|max:255',
            'social_instagram' => 'nullable|url|max:255',
            'social_linkedin' => 'nullable|url|max:255',
            'social_youtube' => 'nullable|url|max:255',
            'social_tiktok' => 'nullable|url|max:255',
            'social_github' => 'nullable|url|max:255',
            'social_website' => 'nullable|url|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ]);
        }

        // Prepare social media data
        $socialMedia = [];
        $socialFields = ['facebook', 'twitter', 'instagram', 'linkedin', 'youtube', 'tiktok', 'github', 'website'];
        
        foreach ($socialFields as $field) {
            $key = 'social_' . $field;
            if ($request->$key) {
                $socialMedia[$field] = $request->$key;
            }
        }

        $member = new Member();
        $member->name = $request->name;
        $member->email = $request->email;
        $member->category_id = $request->category_id;
        $member->position = $request->position;
        $member->bio = $request->bio;
        $member->status = $request->status;
        $member->social_media = !empty($socialMedia) ? json_encode($socialMedia) : null;

        // Handle image upload
        if ($request->hasFile('image')) {
            try {
                $image = $request->file('image');
                $filename = time() . '_' . $image->getClientOriginalName();
                $destinationPath = public_path('/uploads/team');
                
                // Create directory if it doesn't exist
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0777, true);
                }
                
                $image->move($destinationPath, $filename);
                $member->image = '/uploads/team/' . $filename;
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Could not upload image: ' . $e->getMessage()
                ]);
            }
        }

        $member->save();

        return response()->json([
            'success' => true,
            'message' => 'Team member added successfully',
            'redirect' => route('admin.team.index')
        ]);
    }

    /**
     * Show form to edit team member
     */
    public function edit($id)
    {
        $pageTitle = 'Edit Team Member';
        $member = Member::with('category')->findOrFail($id);
        $categories = TeamCategory::orderBy('name')->get();
        
        // Decode social media
        $socialMedia = $member->social_media ? json_decode($member->social_media, true) : [];
        
        return view('admin.team.edit', compact('pageTitle', 'member', 'categories', 'socialMedia'));
    }

    /**
     * Update the specified team member
     */
    public function update(Request $request, $id)
    {
        $member = Member::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'category_id' => 'required|exists:team_categories,id',
            'position' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
            'status' => 'required|in:active,inactive',
            'image' => ['nullable', 'image', new FileTypeValidate(['jpg', 'jpeg', 'png', 'gif'])],
            
            // Social media validation
            'social_facebook' => 'nullable|url|max:255',
            'social_twitter' => 'nullable|url|max:255',
            'social_instagram' => 'nullable|url|max:255',
            'social_linkedin' => 'nullable|url|max:255',
            'social_youtube' => 'nullable|url|max:255',
            'social_tiktok' => 'nullable|url|max:255',
            'social_github' => 'nullable|url|max:255',
            'social_website' => 'nullable|url|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ]);
        }

        // Prepare social media data
        $socialMedia = [];
        $socialFields = ['facebook', 'twitter', 'instagram', 'linkedin', 'youtube', 'tiktok', 'github', 'website'];
        
        foreach ($socialFields as $field) {
            $key = 'social_' . $field;
            if ($request->$key) {
                $socialMedia[$field] = $request->$key;
            }
        }

        $member->name = $request->name;
        $member->email = $request->email;
        $member->category_id = $request->category_id;
        $member->position = $request->position;
        $member->bio = $request->bio;
        $member->status = $request->status;
        $member->social_media = !empty($socialMedia) ? json_encode($socialMedia) : null;

        // Handle image upload
        if ($request->hasFile('image')) {
            try {
                // Delete old image
                if ($member->image && file_exists(public_path($member->image))) {
                    unlink(public_path($member->image));
                }
                
                $image = $request->file('image');
                $filename = time() . '_' . $image->getClientOriginalName();
                $destinationPath = public_path('/uploads/team');
                
                // Create directory if it doesn't exist
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0777, true);
                }
                
                $image->move($destinationPath, $filename);
                $member->image = '/uploads/team/' . $filename;
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Could not upload image: ' . $e->getMessage()
                ]);
            }
        }

        $member->save();

        return response()->json([
            'success' => true,
            'message' => 'Team member updated successfully',
            'redirect' => route('admin.team.index')
        ]);
    }

    /**
     * Delete team member
     */
    public function destroy($id)
    {
        $member = Member::findOrFail($id);
        
        // Delete image file
        if ($member->image && file_exists(public_path($member->image))) {
            @unlink(public_path($member->image));
        }
        
        $member->delete();

        $notify[] = ['success', 'Team member deleted successfully'];
        return back()->withNotify($notify);
    }

    /**
     * Toggle member status
     */
    public function toggleStatus($id)
    {
        $member = Member::findOrFail($id);
        $member->status = $member->status == 'active' ? 'inactive' : 'active';
        $member->save();

        $notify[] = ['success', 'Member status updated successfully'];
        return back()->withNotify($notify);
    }

    /**
     * Bulk actions (delete, status change)
     */
    public function bulkAction(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'action' => 'required|in:delete,active,inactive',
            'ids' => 'required|array',
            'ids.*' => 'exists:members,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ]);
        }

        $ids = $request->ids;

        if ($request->action == 'delete') {
            // Delete images first
            $members = Member::whereIn('id', $ids)->get();
            foreach ($members as $member) {
                if ($member->image && file_exists(public_path($member->image))) {
                    @unlink(public_path($member->image));
                }
            }
            
            Member::whereIn('id', $ids)->delete();
            $message = 'Selected members deleted successfully';
        } else {
            $status = $request->action;
            Member::whereIn('id', $ids)->update([
                'status' => $status
            ]);
            $message = 'Selected members status updated to ' . $status;
        }

        return response()->json([
            'success' => true,
            'message' => $message
        ]);
    }
}