<?php
// app/Http/Controllers/Admin/Fundraisers/CategoryController.php

namespace App\Http\Controllers\Admin\Fundraisers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $pageTitle = 'All Categories';
        $categories = Category::with(['service', 'fundraisers'])->latest()->paginate(getPaginate());
        return view('admin.fundraisers.categories.index', compact('pageTitle', 'categories'));
    }

    public function create()
    {
        $pageTitle = 'Create Category';
        $services = Service::active()->get();
        return view('admin.fundraisers.categories.create', compact('pageTitle', 'services'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'service_id' => 'nullable|exists:services,id',
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:active,inactive',
            'sort_order' => 'nullable|integer|min:0',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
        ]);

        $category = new Category();
        $category->service_id = $request->service_id;
        $category->name = $request->name;
        $category->slug = Str::slug($request->name);
        $category->description = $request->description;
        $category->status = $request->status;
        $category->sort_order = $request->sort_order ?? 0;
        $category->meta_title = $request->meta_title;
        $category->meta_description = $request->meta_description;
        $category->meta_keywords = $request->meta_keywords;
        $category->created_by = auth()->id();

        if ($request->hasFile('image')) {
            try {
                $file = $request->file('image');
                $filename = uniqid() . time() . '.' . $file->getClientOriginalExtension();
                $location = 'assets/images/categories';
                
                if (!file_exists(public_path($location))) {
                    mkdir(public_path($location), 0755, true);
                }
                
                $file->move(public_path($location), $filename);
                $category->image = $location . '/' . $filename;
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Could not upload the image'];
                return back()->withNotify($notify)->withInput();
            }
        }

        $category->save();

        $notify[] = ['success', 'Category created successfully'];
        return redirect()->route('admin.fundraisers.categories.index')->withNotify($notify);
    }

    public function edit(Category $category)
    {
        $pageTitle = 'Edit Category';
        $services = Service::active()->get();
        return view('admin.fundraisers.categories.edit', compact('pageTitle', 'category', 'services'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'service_id' => 'nullable|exists:services,id',
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:active,inactive',
            'sort_order' => 'nullable|integer|min:0',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
        ]);

        $oldImage = $category->image;

        $category->service_id = $request->service_id;
        $category->name = $request->name;
        $category->slug = Str::slug($request->name);
        $category->description = $request->description;
        $category->status = $request->status;
        $category->sort_order = $request->sort_order ?? 0;
        $category->meta_title = $request->meta_title;
        $category->meta_description = $request->meta_description;
        $category->meta_keywords = $request->meta_keywords;
        $category->updated_by = auth()->id();

        if ($request->hasFile('image')) {
            try {
                if ($oldImage && file_exists(public_path($oldImage))) {
                    @unlink(public_path($oldImage));
                }
                
                $file = $request->file('image');
                $filename = uniqid() . time() . '.' . $file->getClientOriginalExtension();
                $location = 'assets/images/categories';
                
                if (!file_exists(public_path($location))) {
                    mkdir(public_path($location), 0755, true);
                }
                
                $file->move(public_path($location), $filename);
                $category->image = $location . '/' . $filename;
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Could not upload the image'];
                return back()->withNotify($notify)->withInput();
            }
        }

        $category->save();

        $notify[] = ['success', 'Category updated successfully'];
        return redirect()->route('admin.fundraisers.categories.index')->withNotify($notify);
    }

    public function toggleStatus(Request $request, Category $category)
    {
        $request->validate([
            'status' => 'required|in:active,inactive'
        ]);

        $category->status = $request->status;
        $category->save();

        $notify[] = ['success', 'Category status updated successfully'];
        return back()->withNotify($notify);
    }

    public function destroy(Category $category)
    {
        if ($category->image && file_exists(public_path($category->image))) {
            @unlink(public_path($category->image));
        }

        $category->delete();

        $notify[] = ['success', 'Category deleted successfully'];
        return back()->withNotify($notify);
    }
}