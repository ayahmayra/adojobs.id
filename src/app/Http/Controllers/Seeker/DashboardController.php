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
}

