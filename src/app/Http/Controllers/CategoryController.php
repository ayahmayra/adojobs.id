<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Job;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of categories
     */
    public function index()
    {
        $categories = Category::active()
            ->withCount('jobs')
            ->orderBy('order')
            ->get();

        return view('categories.index', compact('categories'));
    }

    /**
     * Display jobs for a specific category
     */
    public function show(Category $category)
    {
        $jobs = Job::where('category_id', $category->id)
            ->where('status', 'published')
            ->where(function($query) {
                $query->whereNull('application_deadline')
                      ->orWhere('application_deadline', '>=', now());
            })
            ->with(['employer', 'category'])
            ->latest()
            ->paginate(15);

        // Get related categories (other active categories)
        $relatedCategories = Category::active()
            ->where('id', '!=', $category->id)
            ->withCount('jobs')
            ->orderBy('order')
            ->take(6)
            ->get();

        return view('categories.show', compact('category', 'jobs', 'relatedCategories'));
    }
}
