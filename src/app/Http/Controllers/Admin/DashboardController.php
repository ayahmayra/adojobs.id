<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Job;
use App\Models\Application;
use App\Models\Employer;
use App\Models\Seeker;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard
     */
    public function index()
    {
        // Basic counts
        $totalUsers = User::count();
        $totalJobs = Job::count();
        
        // User statistics
        $stats = [
            // Users
            'total_users' => $totalUsers,
            'total_employers' => Employer::count(),
            'total_seekers' => Seeker::count(),
            'active_users' => User::where('is_active', true)->count(),
            'new_users_this_month' => User::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
            
            // Jobs
            'total_jobs' => $totalJobs,
            'active_jobs' => Job::where('status', 'published')
                ->where('application_deadline', '>=', now())
                ->count(),
            'draft_jobs' => Job::where('status', 'draft')->count(),
            'closed_jobs' => Job::where('status', 'closed')->count(),
            'jobs_this_week' => Job::where('created_at', '>=', now()->subWeek())->count(),
            
            // Applications
            'total_applications' => Application::count(),
            'pending_applications' => Application::where('status', 'pending')->count(),
            'reviewed_applications' => Application::where('status', 'reviewed')->count(),
            'shortlisted_applications' => Application::where('status', 'shortlisted')->count(),
            'hired_applications' => Application::where('status', 'hired')->count(),
            'rejected_applications' => Application::where('status', 'rejected')->count(),
            
            // Metrics
            'avg_applications_per_job' => $totalJobs > 0 ? round(Application::count() / $totalJobs, 1) : 0,
            'application_rate' => $totalJobs > 0 && Application::count() > 0 ? round((Application::where('status', 'hired')->count() / Application::count()) * 100, 1) : 0,
        ];

        // Calculate percentages
        $stats['employers_percentage'] = $totalUsers > 0 ? round(($stats['total_employers'] / $totalUsers) * 100, 1) : 0;
        $stats['seekers_percentage'] = $totalUsers > 0 ? round(($stats['total_seekers'] / $totalUsers) * 100, 1) : 0;

        // Recent data
        $recentUsers = User::latest()->take(5)->get();
        $recentJobs = Job::with(['employer'])->latest()->take(5)->get();
        $recentApplications = Application::with(['job.employer', 'seeker.user'])
            ->latest()
            ->take(5)
            ->get();

        // Top categories by job count
        $topCategories = Category::withCount(['jobs' => function ($query) {
                $query->where('status', 'published');
            }])
            ->having('jobs_count', '>', 0)
            ->orderByDesc('jobs_count')
            ->take(5)
            ->get();

        // Top employers by job count
        $topEmployers = Employer::withCount(['jobs' => function ($query) {
                $query->where('status', 'published');
            }])
            ->with('user')
            ->having('jobs_count', '>', 0)
            ->orderByDesc('jobs_count')
            ->take(5)
            ->get();

        // Top jobs by application count
        $topJobs = Job::withCount('applications')
            ->with('employer')
            ->where('status', 'published')
            ->having('applications_count', '>', 0)
            ->orderByDesc('applications_count')
            ->take(5)
            ->get();

        // Quick alerts
        $alerts = [
            'expired_jobs' => Job::where('status', 'published')
                ->where('application_deadline', '<', now())
                ->count(),
            'pending_applications' => Application::where('status', 'pending')->count(),
            'inactive_employers' => Employer::whereDoesntHave('jobs', function ($query) {
                $query->where('created_at', '>=', now()->subDays(60));
            })->count(),
            'draft_jobs' => Job::where('status', 'draft')->count(),
        ];

        // Chart data - Last 7 days
        $last7Days = collect(range(6, 0))->map(function ($daysAgo) {
            return now()->subDays($daysAgo)->format('Y-m-d');
        });

        // Jobs trend data
        $jobsTrend = $last7Days->map(function ($date) {
            return Job::whereDate('created_at', $date)->count();
        });

        // Applications trend data
        $applicationsTrend = $last7Days->map(function ($date) {
            return Application::whereDate('created_at', $date)->count();
        });

        // Users registration trend data
        $usersTrend = $last7Days->map(function ($date) {
            return User::whereDate('created_at', $date)->count();
        });

        // Jobs by status for pie chart
        $jobsByStatus = [
            'published' => Job::where('status', 'published')->count(),
            'draft' => Job::where('status', 'draft')->count(),
            'closed' => Job::where('status', 'closed')->count(),
        ];

        // Applications by status for donut chart
        $applicationsByStatus = [
            'pending' => Application::where('status', 'pending')->count(),
            'reviewed' => Application::where('status', 'reviewed')->count(),
            'shortlisted' => Application::where('status', 'shortlisted')->count(),
            'hired' => Application::where('status', 'hired')->count(),
            'rejected' => Application::where('status', 'rejected')->count(),
        ];

        // Format dates for chart labels
        $chartLabels = $last7Days->map(function ($date) {
            return \Carbon\Carbon::parse($date)->format('M d');
        });

        return view('admin.dashboard', compact(
            'stats', 
            'recentUsers', 
            'recentJobs', 
            'recentApplications',
            'topCategories',
            'topEmployers',
            'topJobs',
            'alerts',
            'chartLabels',
            'jobsTrend',
            'applicationsTrend',
            'usersTrend',
            'jobsByStatus',
            'applicationsByStatus'
        ));
    }
}

