<?php

namespace App\Http\Controllers\Seeker;

use App\Http\Controllers\Controller;
use App\Http\Controllers\MessageController;
use App\Models\Application;
use App\Models\Job;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    /**
     * Display a listing of seeker's applications
     */
    public function index(Request $request)
    {
        $seeker = auth()->user()->seeker;
        
        $query = $seeker->applications()->with(['job.employer']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $applications = $query->latest()->paginate(20);

        return view('seeker.applications.index', compact('applications'));
    }

    /**
     * Show the application form
     */
    public function create(Job $job)
    {
        $seeker = auth()->user()->seeker;

        if (!$seeker) {
            return redirect()->route('seeker.profile.create')
                ->with('error', 'Please complete your profile before applying for jobs.');
        }

        if ($seeker->hasAppliedToJob($job->id)) {
            return redirect()->route('jobs.show', $job->slug)
                ->with('error', 'You have already applied for this job.');
        }

        if (!$job->isActive()) {
            return redirect()->route('jobs.show', $job->slug)
                ->with('error', 'This job is no longer accepting applications.');
        }

        return view('seeker.applications.create', compact('job'));
    }

    /**
     * Store a new application
     */
    public function store(Request $request, Job $job)
    {
        $seeker = auth()->user()->seeker;

        if ($seeker->hasAppliedToJob($job->id)) {
            return redirect()->route('jobs.show', $job->slug)
                ->with('error', 'You have already applied for this job.');
        }

        $request->validate([
            'cover_letter' => 'required|string|min:50',
        ]);

        $application = Application::create([
            'job_id' => $job->id,
            'seeker_id' => $seeker->id,
            'cover_letter' => $request->cover_letter,
            'cv_path' => $seeker->cv_path,
            'status' => 'pending',
        ]);

        // Increment applications count
        $job->increment('applications_count');

        // Send automated notification to employer
        $this->sendApplicationNotificationToEmployer($application, $job, $seeker);

        return redirect()->route('seeker.applications.index')
            ->with('success', 'Lamaran Anda telah berhasil dikirim!');
    }

    /**
     * Display the specified application
     */
    public function show(Application $application)
    {
        // Check if application belongs to current seeker
        $seeker = auth()->user()->seeker;
        if ($application->seeker_id !== $seeker->id) {
            abort(403, 'Unauthorized action.');
        }
        
        $application->load(['job.employer']);
        
        return view('seeker.applications.show', compact('application'));
    }

    /**
     * Withdraw an application
     */
    public function withdraw(Application $application)
    {
        // Check if application belongs to current seeker
        $seeker = auth()->user()->seeker;
        if ($application->seeker_id !== $seeker->id) {
            abort(403, 'Unauthorized action.');
        }

        if ($application->isProcessed()) {
            return redirect()->back()
                ->with('error', 'You cannot withdraw this application.');
        }

        $application->updateStatus('withdrawn');

        return redirect()->back()
            ->with('success', 'Lamaran berhasil dibatalkan.');
    }

    /**
     * Send notification to employer when new application submitted
     */
    private function sendApplicationNotificationToEmployer($application, $job, $seeker)
    {
        $message = "📋 *Lamaran Baru*\n\n";
        $message .= "Kandidat: {$seeker->user->name}\n";
        $message .= "Posisi: {$job->title}\n";
        $message .= "Tanggal Melamar: " . now()->format('d M Y H:i') . "\n\n";
        $message .= "Silakan cek lamaran di dashboard Anda.";

        // Find or create conversation
        $conversation = Conversation::firstOrCreate(
            [
                'seeker_id' => $seeker->id,
                'employer_id' => $job->employer_id,
                'job_id' => $job->id,
            ],
            [
                'subject' => 'Re: ' . $job->title,
                'last_message_at' => now(),
                'seeker_unread_count' => 0,
                'employer_unread_count' => 0,
            ]
        );

        // Create system message
        Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $seeker->user_id,
            'sender_type' => 'system',
            'message' => $message,
            'read_at' => null,
        ]);

        // Update conversation and increment employer's unread count
        $conversation->update([
            'last_message_at' => now(),
        ]);
        $conversation->increment('employer_unread_count');
    }
}

