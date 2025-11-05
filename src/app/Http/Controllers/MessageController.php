<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\Seeker;
use App\Models\Employer;
use App\Models\Job;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MessageController extends Controller
{
    /**
     * Display inbox with all conversations
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        
        // Get conversations based on user role
        $query = Conversation::with(['seeker.user', 'employer', 'job', 'admin', 'lastMessage'])
            ->active();

        if ($user->isAdmin()) {
            $query->forAdmin($user->id);
        } elseif ($user->isSeeker()) {
            $query->forSeeker($user->seeker->id);
        } elseif ($user->isEmployer()) {
            $query->forEmployer($user->employer->id);
        } else {
            abort(403, 'Unauthorized access');
        }

        // Apply filters
        if ($request->filter === 'unread') {
            $query->unread();
        }

        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search, $user) {
                $q->where('subject', 'like', "%{$search}%");
                
                if ($user->isSeeker()) {
                    $q->orWhereHas('employer', function ($eq) use ($search) {
                        $eq->where('company_name', 'like', "%{$search}%");
                    });
                } elseif ($user->isEmployer()) {
                    $q->orWhereHas('seeker.user', function ($sq) use ($search) {
                        $sq->where('name', 'like', "%{$search}%");
                    });
                }
            });
        }

        $conversations = $query->orderBy('last_message_at', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // Get total unread count
        if ($user->isAdmin()) {
            $unreadCount = Conversation::active()->forAdmin($user->id)->unread()->count();
        } else {
            $unreadCount = Conversation::active()
                ->{$user->isSeeker() ? 'forSeeker' : 'forEmployer'}($user->{$user->isSeeker() ? 'seeker' : 'employer'}->id)
                ->unread()
                ->count();
        }

        return view('messages.index', compact('conversations', 'unreadCount'));
    }

    /**
     * Show a specific conversation
     */
    public function show(Conversation $conversation)
    {
        $user = auth()->user();

        // Check if user is participant
        if (!$conversation->isParticipant($user)) {
            abort(403, 'Unauthorized access to this conversation');
        }

        // Load messages
        $conversation->load(['messages.sender', 'seeker.user', 'employer', 'admin', 'job']);

        // Mark conversation as read
        $conversation->markAsRead();

        return view('messages.show', compact('conversation'));
    }

    /**
     * Send a message in a conversation
     */
    public function store(Request $request, Conversation $conversation)
    {
        $user = auth()->user();

        // Check if user is participant
        if (!$conversation->isParticipant($user)) {
            abort(403, 'Unauthorized access to this conversation');
        }

        $request->validate([
            'message' => 'required|string|max:2000',
        ]);

        // Determine sender type and recipient
        if ($user->isAdmin()) {
            $senderType = 'admin';
            // Recipient is either seeker or employer
            if ($conversation->seeker_id) {
                $recipientType = 'seeker';
            } elseif ($conversation->employer_id) {
                $recipientType = 'employer';
            }
        } elseif ($user->isSeeker()) {
            $senderType = 'seeker';
            $recipientType = $conversation->admin_id ? 'admin' : 'employer';
        } elseif ($user->isEmployer()) {
            $senderType = 'employer';
            $recipientType = $conversation->admin_id ? 'admin' : 'seeker';
        }

        // Create message
        $message = Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $user->id,
            'sender_type' => $senderType,
            'message' => $request->message,
        ]);

        // Update conversation
        $conversation->update([
            'last_message_at' => now(),
        ]);

        // Increment unread count for recipient
        $conversation->incrementUnreadCount($recipientType);

        // TODO: Send email notification (optional)
        // TODO: Broadcast via WebSocket (future enhancement)

        return redirect()->route('messages.show', $conversation)
            ->with('success', 'Message sent successfully');
    }

    /**
     * Start a new conversation or get existing one
     */
    public function startConversation(Request $request)
    {
        $user = auth()->user();

        // Scenario 0: Contact admin (any user → admin)
        if ($request->has('contact_admin')) {
            return $this->handleContactAdmin($request, $user);
        }

        // Scenario 1: Admin contacts user (admin → employer/seeker)
        if ($user->isAdmin() && $request->has('user_id')) {
            return $this->handleAdminToUserContact($request, $user);
        }

        // Scenario 2: Direct contact via user_id (seeker <-> employer)
        if ($request->has('user_id')) {
            return $this->handleDirectContact($request, $user);
        }

        // Scenario 3: Contact via employer_id (seeker contacts employer from listing/profile)
        if ($request->has('employer_id') && $user->isSeeker()) {
            return $this->handleSeekerToEmployerContact($request, $user);
        }

        // Scenario 4: Contact via job_id (seeker contacts employer about specific job)
        if ($request->has('job_id') && !$request->has('application_id')) {
            return $this->handleJobInquiry($request, $user);
        }

        // Scenario 4: Original flow with job_id and application_id
        $request->validate([
            'job_id' => 'required|exists:job_postings,id',
            'application_id' => 'nullable|exists:applications,id',
        ]);

        $job = Job::findOrFail($request->job_id);

        // Determine seeker and employer IDs
        if ($user->isSeeker()) {
            // For seeker, we should have an application_id
            if ($request->application_id) {
                $application = \App\Models\Application::findOrFail($request->application_id);
                
                // Verify this application belongs to the current seeker
                if ($application->seeker_id !== $user->seeker->id) {
                    abort(403, 'Unauthorized action');
                }
                
                $seekerId = $user->seeker->id;
                $employerId = $job->employer_id;
            } else {
                abort(400, 'Application ID is required for seekers');
            }
        } elseif ($user->isEmployer()) {
            // For employer, we need application_id to know which seeker
            if (!$request->application_id) {
                abort(400, 'Application ID is required');
            }
            
            $application = \App\Models\Application::findOrFail($request->application_id);
            
            // Verify this application is for a job posted by this employer
            if ($application->job->employer_id !== $user->employer->id) {
                abort(403, 'Unauthorized action');
            }
            
            $seekerId = $application->seeker_id;
            $employerId = $user->employer->id;
        } else {
            abort(403, 'Unauthorized action');
        }

        // Check if conversation already exists
        $conversation = Conversation::where('seeker_id', $seekerId)
            ->where('employer_id', $employerId)
            ->where('job_id', $job->id)
            ->first();

        // Create new conversation if doesn't exist
        if (!$conversation) {
            $conversation = Conversation::create([
                'seeker_id' => $seekerId,
                'employer_id' => $employerId,
                'job_id' => $job->id,
                'subject' => 'Re: ' . $job->title,
                'last_message_at' => now(),
            ]);
        }

        return redirect()->route('messages.show', $conversation);
    }

    /**
     * Delete a conversation
     */
    public function destroy(Conversation $conversation)
    {
        $user = auth()->user();

        // Check if user is participant
        if (!$conversation->isParticipant($user)) {
            abort(403, 'Unauthorized access to this conversation');
        }

        // Soft delete by archiving
        $conversation->update(['is_archived' => true]);

        return redirect()->route('messages.index')
            ->with('success', 'Conversation archived successfully');
    }

    /**
     * Get unread count (for AJAX/API)
     */
    public function unreadCount()
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json(['count' => 0]);
        }

        if ($user->isAdmin()) {
            $count = Conversation::active()->forAdmin($user->id)->unread()->count();
        } else {
            $count = Conversation::active()
                ->{$user->isSeeker() ? 'forSeeker' : 'forEmployer'}($user->{$user->isSeeker() ? 'seeker' : 'employer'}->id)
                ->unread()
                ->count();
        }

        return response()->json(['count' => $count]);
    }

    /**
     * Handle direct contact between users
     */
    private function handleDirectContact(Request $request, $user)
    {
        $targetUserId = $request->user_id;
        $targetUser = User::with(['seeker', 'employer'])->findOrFail($targetUserId);
        
        // Also load current user's relationships if needed
        if (!$user->relationLoaded('employer')) {
            $user->load('employer');
        }
        if (!$user->relationLoaded('seeker')) {
            $user->load('seeker');
        }
        
        // Check based on both role and actual relationship existence
        // This handles cases where role might not match the relationship
        $userIsEmployer = $user->isEmployer() || ($user->employer !== null);
        $userIsSeeker = $user->isSeeker() || ($user->seeker !== null);
        $targetIsSeeker = $targetUser->isSeeker() || ($targetUser->seeker !== null);
        $targetIsEmployer = $targetUser->isEmployer() || ($targetUser->employer !== null);
        
        // Debug: Log the roles and relationships for troubleshooting
        \Log::debug('Direct contact attempt', [
            'user_id' => $user->id,
            'user_role' => $user->role,
            'user_is_employer' => $userIsEmployer,
            'user_is_seeker' => $userIsSeeker,
            'user_has_employer' => $user->employer ? 'yes' : 'no',
            'user_has_seeker' => $user->seeker ? 'yes' : 'no',
            'target_user_id' => $targetUser->id,
            'target_user_role' => $targetUser->role,
            'target_is_seeker' => $targetIsSeeker,
            'target_is_employer' => $targetIsEmployer,
            'target_has_seeker' => $targetUser->seeker ? 'yes' : 'no',
            'target_has_employer' => $targetUser->employer ? 'yes' : 'no',
            'target_is_active' => $targetUser->is_active,
        ]);
        
        if ($userIsEmployer && $targetIsSeeker) {
            // Employer contacting seeker
            // Validate that target user has an active seeker profile
            if (!$targetUser->seeker || !$targetUser->is_active) {
                return redirect()->back()
                    ->with('error', 'Kandidat tidak aktif atau tidak memiliki profil lengkap.');
            }
            
            // Validate that current user has an active employer profile
            if (!$user->employer) {
                return redirect()->back()
                    ->with('error', 'Silakan lengkapi profil perusahaan terlebih dahulu.');
            }
            
            $seekerId = $targetUser->seeker->id;
            $employerId = $user->employer->id;
            $subject = 'Diskusi Peluang Kerja dengan ' . $targetUser->name;
        } elseif ($userIsSeeker && $targetIsEmployer) {
            // Seeker contacting employer
            // Validate that current user has an active seeker profile
            if (!$user->seeker || !$user->is_active) {
                return redirect()->back()
                    ->with('error', 'Silakan lengkapi profil Anda terlebih dahulu.');
            }
            
            // Validate that target user has an active employer profile
            if (!$targetUser->employer || !$targetUser->is_active) {
                return redirect()->back()
                    ->with('error', 'Rekruter tidak aktif atau tidak memiliki profil lengkap.');
            }
            
            $seekerId = $user->seeker->id;
            $employerId = $targetUser->employer->id;
            $subject = 'Pertanyaan tentang ' . $targetUser->employer->company_name;
        } else {
            // Provide more detailed error message
            $errorMsg = sprintf(
                'Invalid contact attempt. Current user: %s (ID: %d, Role: %s), Target user: %s (ID: %d, Role: %s)',
                $user->name,
                $user->id,
                $user->role,
                $targetUser->name,
                $targetUser->id,
                $targetUser->role
            );
            
            \Log::error('Invalid contact attempt', [
                'current_user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'role' => $user->role,
                    'is_employer' => $userIsEmployer,
                    'is_seeker' => $userIsSeeker,
                    'has_employer' => $user->employer ? 'yes' : 'no',
                    'has_seeker' => $user->seeker ? 'yes' : 'no',
                ],
                'target_user' => [
                    'id' => $targetUser->id,
                    'name' => $targetUser->name,
                    'role' => $targetUser->role,
                    'is_employer' => $targetIsEmployer,
                    'is_seeker' => $targetIsSeeker,
                    'has_employer' => $targetUser->employer ? 'yes' : 'no',
                    'has_seeker' => $targetUser->seeker ? 'yes' : 'no',
                    'is_active' => $targetUser->is_active,
                ]
            ]);
            
            return redirect()->back()
                ->with('error', 'Tidak dapat mengirim pesan. Pastikan Anda adalah rekruter dan kandidat adalah pencari kerja yang aktif.');
        }
        
        // Check if conversation already exists (without specific job)
        $conversation = Conversation::where('seeker_id', $seekerId)
            ->where('employer_id', $employerId)
            ->whereNull('job_id')
            ->first();
        
        // Create new conversation if doesn't exist
        if (!$conversation) {
            $conversation = Conversation::create([
                'seeker_id' => $seekerId,
                'employer_id' => $employerId,
                'job_id' => null,
                'subject' => $subject,
                'last_message_at' => now(),
            ]);
        }
        
        return redirect()->route('messages.show', $conversation);
    }

    /**
     * Handle seeker contacting employer from listing/profile
     */
    private function handleSeekerToEmployerContact(Request $request, $user)
    {
        if (!$user->isSeeker()) {
            abort(403, 'Only seekers can use this method');
        }

        $employer = Employer::findOrFail($request->employer_id);
        $seekerId = $user->seeker->id;
        $employerId = $employer->id;
        
        // Check if conversation already exists (without specific job)
        $conversation = Conversation::where('seeker_id', $seekerId)
            ->where('employer_id', $employerId)
            ->whereNull('job_id')
            ->first();
        
        // Create new conversation if doesn't exist
        if (!$conversation) {
            $conversation = Conversation::create([
                'seeker_id' => $seekerId,
                'employer_id' => $employerId,
                'job_id' => null,
                'subject' => 'Pertanyaan tentang ' . $employer->company_name,
                'last_message_at' => now(),
            ]);
        }
        
        return redirect()->route('messages.show', $conversation);
    }

    /**
     * Handle job inquiry without application
     */
    private function handleJobInquiry(Request $request, $user)
    {
        if (!$user->isSeeker()) {
            abort(403, 'Only seekers can inquire about jobs');
        }

        $job = Job::findOrFail($request->job_id);
        $seekerId = $user->seeker->id;
        $employerId = $job->employer_id;
        
        // Check if conversation already exists for this job
        $conversation = Conversation::where('seeker_id', $seekerId)
            ->where('employer_id', $employerId)
            ->where('job_id', $job->id)
            ->first();
        
        // Create new conversation if doesn't exist
        if (!$conversation) {
            $conversation = Conversation::create([
                'seeker_id' => $seekerId,
                'employer_id' => $employerId,
                'job_id' => $job->id,
                'subject' => 'Pertanyaan tentang: ' . $job->title,
                'last_message_at' => now(),
            ]);
        }
        
        return redirect()->route('messages.show', $conversation);
    }

    /**
     * Handle user contacting admin
     */
    private function handleContactAdmin(Request $request, $user)
    {
        // Get first admin user (or specific admin if needed)
        $admin = User::where('role', 'admin')->where('is_active', true)->first();
        
        if (!$admin) {
            return redirect()->back()->with('error', 'Admin tidak tersedia saat ini.');
        }

        // Determine seeker_id and employer_id based on user role
        $seekerId = null;
        $employerId = null;
        $subject = 'Pertanyaan untuk Admin';
        
        if ($user->isSeeker()) {
            $seekerId = $user->seeker->id;
            $subject = 'Pertanyaan dari ' . $user->name;
        } elseif ($user->isEmployer()) {
            $employerId = $user->employer->id;
            $subject = 'Pertanyaan dari ' . $user->employer->company_name;
        }

        // Check if conversation already exists
        $conversation = Conversation::where('admin_id', $admin->id)
            ->where(function ($query) use ($seekerId, $employerId) {
                if ($seekerId) {
                    $query->where('seeker_id', $seekerId);
                }
                if ($employerId) {
                    $query->where('employer_id', $employerId);
                }
            })
            ->whereNull('job_id')
            ->first();

        // Create new conversation if doesn't exist
        if (!$conversation) {
            $conversation = Conversation::create([
                'seeker_id' => $seekerId,
                'employer_id' => $employerId,
                'admin_id' => $admin->id,
                'job_id' => null,
                'subject' => $subject,
                'last_message_at' => now(),
            ]);
        }

        return redirect()->route('messages.show', $conversation);
    }

    /**
     * Handle admin contacting user
     */
    private function handleAdminToUserContact(Request $request, $user)
    {
        if (!$user->isAdmin()) {
            abort(403, 'Only admin can use this method');
        }

        $targetUserId = $request->user_id;
        $targetUser = User::findOrFail($targetUserId);

        $seekerId = null;
        $employerId = null;
        $subject = 'Pesan dari Admin';

        if ($targetUser->isSeeker()) {
            // Admin contacting seeker
            $seekerId = $targetUser->seeker->id;
            $subject = 'Pesan untuk ' . $targetUser->name;
        } elseif ($targetUser->isEmployer()) {
            // Admin contacting employer
            $employerId = $targetUser->employer->id;
            $subject = 'Pesan untuk ' . $targetUser->employer->company_name;
        } else {
            abort(400, 'Invalid user type for messaging');
        }

        // Check if conversation already exists
        $conversation = Conversation::where('admin_id', $user->id)
            ->where(function ($query) use ($seekerId, $employerId) {
                if ($seekerId) {
                    $query->where('seeker_id', $seekerId);
                }
                if ($employerId) {
                    $query->where('employer_id', $employerId);
                }
            })
            ->whereNull('job_id')
            ->first();

        // Create new conversation if doesn't exist
        if (!$conversation) {
            $conversation = Conversation::create([
                'seeker_id' => $seekerId,
                'employer_id' => $employerId,
                'admin_id' => $user->id,
                'job_id' => null,
                'subject' => $subject,
                'last_message_at' => now(),
            ]);
        }

        return redirect()->route('messages.show', $conversation);
    }
}
