<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Models\Application;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the employer dashboard
     */
    public function index()
    {
        $employer = auth()->user()->employer;

        if (!$employer) {
            return redirect()->route('employer.profile.create')
                ->with('info', 'Please complete your company profile first.');
        }

        // Statistics
        $jobIds = $employer->jobs()->pluck('id');
        
        $stats = [
            'total_jobs' => $employer->jobs()->count(),
            'active_jobs' => $employer->activeJobs()->count(),
            'total_applications' => Application::whereIn('job_id', $jobIds)->count(),
            'pending_applications' => Application::whereIn('job_id', $jobIds)->pending()->count(),
            'reviewed_applications' => Application::whereIn('job_id', $jobIds)->where('status', 'reviewed')->count(),
            'shortlisted_applications' => Application::whereIn('job_id', $jobIds)->where('status', 'shortlisted')->count(),
            'accepted_applications' => Application::whereIn('job_id', $jobIds)->where('status', 'accepted')->count(),
            'rejected_applications' => Application::whereIn('job_id', $jobIds)->where('status', 'rejected')->count(),
            'favorite_candidates' => $employer->savedCandidates()->count(),
        ];

        // Recent data
        $recentJobs = $employer->jobs()
            ->with(['category'])
            ->withCount('applications')
            ->latest()
            ->take(5)
            ->get();
            
        $recentApplications = Application::whereIn('job_id', $jobIds)
            ->with(['job', 'seeker.user'])
            ->latest()
            ->take(5)
            ->get();

        // Unread messages count
        $unreadMessages = \App\Models\Conversation::active()
            ->forEmployer($employer->id)
            ->unread()
            ->count();

        // Recent messages
        $recentConversations = \App\Models\Conversation::active()
            ->forEmployer($employer->id)
            ->with(['seeker.user', 'lastMessage'])
            ->latest('updated_at')
            ->take(3)
            ->get();

        // Favorite candidates (seekers)
        $favoriteCandidates = $employer->savedCandidates()
            ->with(['seeker.user'])
            ->latest()
            ->take(5)
            ->get();

        return view('employer.dashboard', compact(
            'employer', 
            'stats', 
            'recentJobs', 
            'recentApplications',
            'unreadMessages',
            'recentConversations',
            'favoriteCandidates'
        ));
    }
}

