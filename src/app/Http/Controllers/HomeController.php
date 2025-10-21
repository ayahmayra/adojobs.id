<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\Category;
use App\Models\Employer;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display the home page
     */
    public function index()
    {
        $featuredJobs = Job::active()
            ->featured()
            ->with(['employer', 'category'])
            ->take(6)
            ->get();

        $recentJobs = Job::active()
            ->with(['employer', 'category'])
            ->latest('published_at')
            ->take(8)
            ->get();

        $categories = Category::active()
            ->ordered()
            ->withCount(['jobs' => function ($query) {
                $query->active();
            }])
            ->take(8)
            ->get();

        $stats = [
            'total_jobs' => Job::active()->count(),
            'total_companies' => Employer::verified()->count(),
            'total_categories' => Category::active()->count(),
        ];

        return view('home', compact('featuredJobs', 'recentJobs', 'categories', 'stats'));
    }

    /**
     * Display the about page
     */
    public function about()
    {
        return view('about');
    }

    /**
     * Display the contact page
     */
    public function contact()
    {
        return view('contact');
    }
}

