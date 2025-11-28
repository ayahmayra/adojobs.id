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
            ->whereHas('employer.user', function($q) {
                $q->where('is_active', true);
            })
            ->with(['employer', 'category'])
            ->take(6)
            ->get();

        $recentJobs = Job::active()
            ->whereHas('employer.user', function($q) {
                $q->where('is_active', true);
            })
            ->with(['employer', 'category'])
            ->latest('published_at')
            ->take(8)
            ->get();

        $categories = Category::active()
            ->ordered()
            ->withCount(['jobs' => function ($query) {
                $query->active()
                    ->whereHas('employer.user', function($q) {
                        $q->where('is_active', true);
                    });
            }])
            ->take(8)
            ->get();

        $stats = [
            'total_jobs' => Job::active()
                ->whereHas('employer.user', function($q) {
                    $q->where('is_active', true);
                })
                ->count(),
            'total_companies' => Employer::whereHas('user', function($q) {
                $q->where('is_active', true);
            })->count(),
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

