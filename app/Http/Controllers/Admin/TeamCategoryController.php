<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TeamCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class TeamCategoryController extends Controller
{
    /**
     * Display a listing of categories
     */
    public function index(Request $request)
    {
        $pageTitle = 'Team Categories';
        
        $categories = TeamCategory::withCount('members')
            ->when($request->search, function($query) use ($request) {
                $query->where('name', 'like', "%{$request->search}%");
            })
            ->when($request->status !== null, function($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(getPaginate());

        return view('admin.team.categories.index', compact('pageTitle', 'categories'));
    }

    /**
     * Store a newly created category
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:team_categories,name',
            'description' => 'nullable|string|max:500',
            'status' => 'required|boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ]);
        }

        $category = new TeamCategory();
        $category->name = $request->name;
        $category->slug = Str::slug($request->name);
        $category->description = $request->description;
        $category->status = $request->status;
        $category->sort_order = $request->sort_order ?? 0;
        $category->save();

        return response()->json([
            'success' => true,
            'message' => 'Category created successfully',
            'data' => $category
        ]);
    }

    /**
     * Update the specified category
     */
    public function update(Request $request, $id)
    {
        $category = TeamCategory::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:team_categories,name,' . $id,
            'description' => 'nullable|string|max:500',
            'status' => 'required|boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ]);
        }

        $category->name = $request->name;
        $category->slug = Str::slug($request->name);
        $category->description = $request->description;
        $category->status = $request->status;
        $category->sort_order = $request->sort_order ?? 0;
        $category->save();

        return response()->json([
            'success' => true,
            'message' => 'Category updated successfully',
            'data' => $category
        ]);
    }

    /**
     * Remove the specified category
     */
    public function destroy($id)
    {
        $category = TeamCategory::findOrFail($id);
        
        // Check if category has members
        if ($category->members()->count() > 0) {
            $notify[] = ['error', 'Cannot delete category with existing members'];
            return back()->withNotify($notify);
        }

        $category->delete();

        $notify[] = ['success', 'Category deleted successfully'];
        return back()->withNotify($notify);
    }

    /**
     * Toggle category status
     */
    public function toggleStatus($id)
    {
        $category = TeamCategory::findOrFail($id);
        $category->status = !$category->status;
        $category->save();

        $notify[] = ['success', 'Category status updated successfully'];
        return back()->withNotify($notify);
    }

    /**
     * Get all categories for select2
     */
    public function getSelect2(Request $request)
    {
        $categories = TeamCategory::active()
            ->when($request->search, function($query) use ($request) {
                $query->where('name', 'like', "%{$request->search}%");
            })
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get(['id', 'name']);

        return response()->json($categories);
    }
}