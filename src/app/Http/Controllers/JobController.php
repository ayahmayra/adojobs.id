<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\Category;
use Illuminate\Http\Request;

class JobController extends Controller
{
    /**
     * Display a listing of jobs
     */
    public function index(Request $request)
    {
        $query = Job::query()->active()->with(['employer', 'category']);

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Filter by job type
        if ($request->filled('job_type')) {
            $query->where('job_type', $request->job_type);
        }

        // Filter by work mode
        if ($request->filled('work_mode')) {
            $query->where('work_mode', $request->work_mode);
        }

        // Filter by location
        if ($request->filled('location')) {
            $query->where('city', 'like', '%' . $request->location . '%');
        }

        // Search by keyword
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->where('title', 'like', "%{$keyword}%")
                  ->orWhere('description', 'like', "%{$keyword}%");
            });
        }

        $jobs = $query->latest('published_at')->paginate(20);
        $categories = Category::active()->ordered()->get();

        return view('jobs.index', compact('jobs', 'categories'));
    }

    /**
     * Display the specified job
     */
    public function show($slug)
    {
        $job = Job::where('slug', $slug)
            ->with(['employer.user', 'category', 'applications'])
            ->firstOrFail();

        // Increment view count
        $job->incrementViews();

        // Get similar jobs
        $similarJobs = Job::active()
            ->where('id', '!=', $job->id)
            ->where('category_id', $job->category_id)
            ->with(['employer', 'category'])
            ->take(4)
            ->get();

        return view('jobs.show', compact('job', 'similarJobs'));
    }
}

