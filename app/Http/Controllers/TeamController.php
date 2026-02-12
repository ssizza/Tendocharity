<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\TeamCategory;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TeamController extends Controller
{
    public function index()
    {
        $pageTitle = 'Our Team';
        
        // Try to get team page from Pages table
        $sections = Page::where('slug', 'team')->first();
        
        $seoContents = null;
        $seoImage = null;
        
        if ($sections && $sections->seo_content) {
            $seoContents = $sections->seo_content;
            $seoImage = @$seoContents->image ? getImage(getFilePath('seo') . '/' . @$seoContents->image, getFileSize('seo')) : null;
        }
        
        // Get all active members with their categories
        $members = Member::with('category')
            ->active()
            ->orderBy('created_at', 'desc')
            ->get();
            
        // Get all active team categories - ordered by sort_order and name
        $categories = TeamCategory::active()
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();
        
        return view('team.index', compact(
            'pageTitle', 
            'sections', 
            'seoContents', 
            'seoImage', 
            'members', 
            'categories'
        ));
    }

    public function show($id, $slug = null)
    {
        $member = Member::with('category')
            ->active()
            ->findOrFail($id);
            
        $pageTitle = $member->name;
        
        $seoContents = null;
        $seoImage = null;
        
        // Generate SEO content from member data
        $seoContents = (object) [
            'heading' => $member->name . ' - ' . $member->position,
            'description' => strip_tags(substr($member->bio, 0, 160)) . '...',
        ];
        
        // Get related members from same category
        $relatedMembers = Member::where('category_id', $member->category_id)
            ->where('id', '!=', $member->id)
            ->active()
            ->orderBy('created_at', 'desc')
            ->take(4)
            ->get();
        
        return view('team.show', compact(
            'pageTitle', 
            'member', 
            'seoContents', 
            'seoImage', 
            'relatedMembers'
        ));
    }

    public function category($slug)
    {
        $category = TeamCategory::where('slug', $slug)
            ->active()
            ->firstOrFail();
            
        $pageTitle = $category->name . ' Team';
        
        $sections = Page::where('slug', 'team')->first();
        
        $seoContents = null;
        $seoImage = null;
        
        if ($sections && $sections->seo_content) {
            $seoContents = $sections->seo_content;
            $seoImage = @$seoContents->image ? getImage(getFilePath('seo') . '/' . @$seoContents->image, getFileSize('seo')) : null;
        }
        
        $members = Member::where('category_id', $category->id)
            ->active()
            ->orderBy('created_at', 'desc')
            ->get();
            
        $categories = TeamCategory::active()
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();
        
        return view('team.category', compact(
            'pageTitle', 
            'sections', 
            'seoContents', 
            'seoImage', 
            'category',
            'members', 
            'categories'
        ));
    }
}