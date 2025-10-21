<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Job;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    /**
     * Display a listing of applications
     */
    public function index(Request $request)
    {
        $employer = auth()->user()->employer;
        $jobIds = $employer->jobs()->pluck('id');

        $query = Application::whereIn('job_id', $jobIds)->with(['job', 'seeker.user']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('job_id')) {
            $query->where('job_id', $request->job_id);
        }

        $applications = $query->latest()->paginate(20);
        $jobs = $employer->jobs()->get(['id', 'title']);

        return view('employer.applications.index', compact('applications', 'jobs'));
    }

    /**
     * Display the specified application
     */
    public function show(Application $application)
    {
        // Check if application belongs to current employer's job
        $employer = auth()->user()->employer;
        if ($application->job->employer_id !== $employer->id) {
            abort(403, 'Unauthorized action.');
        }
        
        $application->load(['job', 'seeker.user']);
        
        return view('employer.applications.show', compact('application'));
    }

    /**
     * Update the application status
     */
    public function updateStatus(Request $request, Application $application)
    {
        // Check if application belongs to current employer's job
        $employer = auth()->user()->employer;
        if ($application->job->employer_id !== $employer->id) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'status' => 'required|in:pending,reviewed,shortlisted,interview,offered,hired,rejected',
            'employer_notes' => 'nullable|string',
            'message_to_seeker' => 'nullable|string|max:1000',
        ]);

        $oldStatus = $application->status;
        
        // Load necessary relationships BEFORE doing anything
        $application->load(['job.employer', 'seeker.user']);
        
        // Store application data we need for notification
        $jobTitle = $application->job->title;
        $companyName = $application->job->employer->company_name;
        $seekerId = $application->seeker_id;
        $employerId = $application->job->employer_id;
        $employerUserId = $application->job->employer->user_id;
        $jobId = $application->job_id;
        
        // Update status (internal notes only)
        $application->updateStatus($request->status, $request->employer_notes);

        // Send notification to seeker if status changed (using message_to_seeker, NOT employer_notes)
        if ($oldStatus !== $request->status) {
            try {
                $this->sendStatusUpdateNotificationToSeeker(
                    $application, 
                    $request->status, 
                    $request->message_to_seeker, // Use message_to_seeker instead of employer_notes
                    $jobTitle,
                    $companyName,
                    $seekerId,
                    $employerId,
                    $employerUserId,
                    $jobId
                );
            } catch (\Exception $e) {
                \Log::error('Failed to send status update notification: ' . $e->getMessage());
                \Log::error('Stack trace: ' . $e->getTraceAsString());
                // Continue execution even if notification fails
            }
        }

        return redirect()->back()
            ->with('success', 'Status lamaran berhasil diperbarui.');
    }

    /**
     * Send notification to seeker when application status changes
     */
    private function sendStatusUpdateNotificationToSeeker(
        $application, 
        $newStatus, 
        $notes = null,
        $jobTitle = null,
        $companyName = null,
        $seekerId = null,
        $employerId = null,
        $employerUserId = null,
        $jobId = null
    ) {
        // Use passed parameters or fall back to application relationships
        $jobTitle = $jobTitle ?? $application->job->title;
        $companyName = $companyName ?? $application->job->employer->company_name;
        $seekerId = $seekerId ?? $application->seeker_id;
        $employerId = $employerId ?? $application->job->employer_id;
        $employerUserId = $employerUserId ?? $application->job->employer->user_id;
        $jobId = $jobId ?? $application->job_id;
        
        $statusLabels = [
            'pending' => 'Menunggu Review',
            'reviewed' => 'Telah Direview',
            'shortlisted' => 'Masuk Shortlist',
            'interview' => 'Undangan Interview',
            'offered' => 'Penawaran Kerja',
            'hired' => 'Diterima',
            'rejected' => 'Ditolak',
        ];

        $statusEmojis = [
            'pending' => '⏳',
            'reviewed' => '👀',
            'shortlisted' => '⭐',
            'interview' => '📅',
            'offered' => '🎉',
            'hired' => '✅',
            'rejected' => '❌',
        ];

        $message = ($statusEmojis[$newStatus] ?? '📝') . " *Pembaruan Status Lamaran*\n\n";
        $message .= "Posisi: {$jobTitle}\n";
        $message .= "Perusahaan: {$companyName}\n";
        $message .= "Status Baru: " . ($statusLabels[$newStatus] ?? $newStatus) . "\n";
        $message .= "Tanggal: " . now()->format('d M Y H:i') . "\n";
        
        if ($notes) {
            $message .= "\n💬 Pesan dari Perekrut:\n" . $notes;
        }

        // Find or create conversation
        $conversation = Conversation::firstOrCreate(
            [
                'seeker_id' => $seekerId,
                'employer_id' => $employerId,
                'job_id' => $jobId,
            ],
            [
                'subject' => 'Re: ' . $jobTitle,
                'last_message_at' => now(),
                'seeker_unread_count' => 0,
                'employer_unread_count' => 0,
            ]
        );

        // Create system message
        Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $employerUserId,
            'sender_type' => 'system',
            'message' => $message,
            'read_at' => null,
        ]);

        // Update conversation and increment seeker's unread count
        $conversation->update([
            'last_message_at' => now(),
        ]);
        $conversation->increment('seeker_unread_count');
    }
}

