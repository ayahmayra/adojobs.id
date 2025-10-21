<?php

namespace App\Http\Controllers\Seeker;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the seeker dashboard
     */
    public function index()
    {
        $seeker = auth()->user()->seeker;

        if (!$seeker) {
            return redirect()->route('seeker.profile.create')
                ->with('info', 'Silakan lengkapi profil Anda terlebih dahulu.');
        }

        $stats = [
            'total_applications' => $seeker->applications()->count(),
            'pending_applications' => $seeker->applications()->pending()->count(),
            'shortlisted_applications' => $seeker->applications()->shortlisted()->count(),
            'accepted_applications' => $seeker->applications()->where('status', 'hired')->count(),
            'rejected_applications' => $seeker->applications()->where('status', 'rejected')->count(),
            'saved_jobs' => $seeker->savedJobs()->count(),
        ];

        $recentApplications = $seeker->applications()
            ->with(['job.employer'])
            ->latest()
            ->take(5)
            ->get();

        $savedJobs = $seeker->savedJobs()
            ->with(['job.employer', 'job.category'])
            ->latest()
            ->take(5)
            ->get();

        // Get unread messages count
        $unreadMessages = \App\Models\Conversation::active()
            ->forSeeker($seeker->id)
            ->unread()
            ->count();

        // Get recent conversations
        $recentConversations = \App\Models\Conversation::active()
            ->forSeeker($seeker->id)
            ->with(['employer.user', 'lastMessage'])
            ->latest('updated_at')
            ->take(3)
            ->get();


        return view('seeker.dashboard', compact(
            'seeker', 
            'stats', 
            'recentApplications', 
            'savedJobs',
            'unreadMessages',
            'recentConversations'
        ));
    }

    /**
     * Browse all available jobs
     */
    public function browseJobs(Request $request)
    {
        $query = \App\Models\Job::query()
            ->with(['employer', 'category'])
            ->where('status', 'published')
            ->where(function($q) {
                $q->whereNull('application_deadline')
                  ->orWhere('application_deadline', '>=', now());
            });

        // Search by keyword
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->where('title', 'like', "%{$keyword}%")
                  ->orWhere('description', 'like', "%{$keyword}%");
            });
        }

        // Filter by location
        if ($request->filled('location')) {
            $query->where('city', 'like', "%{$request->location}%");
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Filter by job type
        if ($request->filled('job_type')) {
            $query->where('job_type', $request->job_type);
        }

        $jobs = $query->latest('created_at')->paginate(15);
        $categories = \App\Models\Category::orderBy('name')->get();

        return view('seeker.jobs', compact('jobs', 'categories'));
    }
}

