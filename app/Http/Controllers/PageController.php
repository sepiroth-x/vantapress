<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
    /**
     * Display a page by its slug
     */
    public function show($slug)
    {
        // Find the page by slug
        $page = Page::where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();
        
        // Load the page with its relationships
        $page->load(['author', 'featuredImage']);
        
        // Return a simple view for now (we can enhance this later with themes)
        return view('pages.show', compact('page'));
    }
}
