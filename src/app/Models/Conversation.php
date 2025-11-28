<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    use HasFactory;

    protected $fillable = [
        'seeker_id',
        'employer_id',
        'admin_id',
        'job_id',
        'subject',
        'last_message_at',
        'seeker_unread_count',
        'employer_unread_count',
        'admin_unread_count',
        'is_archived',
    ];

    protected $casts = [
        'last_message_at' => 'datetime',
        'is_archived' => 'boolean',
    ];

    /**
     * Get the seeker that owns the conversation.
     */
    public function seeker()
    {
        return $this->belongsTo(Seeker::class);
    }

    /**
     * Get the employer that owns the conversation.
     */
    public function employer()
    {
        return $this->belongsTo(Employer::class);
    }

    /**
     * Get the job associated with the conversation.
     */
    public function job()
    {
        return $this->belongsTo(Job::class, 'job_id');
    }

    /**
     * Get the admin user associated with the conversation.
     */
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    /**
     * Get all messages for the conversation.
     */
    public function messages()
    {
        return $this->hasMany(Message::class)->orderBy('created_at', 'asc');
    }

    /**
     * Get the last message of the conversation.
     */
    public function lastMessage()
    {
        return $this->hasOne(Message::class)->latestOfMany();
    }

    /**
     * Get unread count for current user
     */
    public function getUnreadCountAttribute()
    {
        if (auth()->check()) {
            if (auth()->user()->isAdmin()) {
                return $this->admin_unread_count;
            } elseif (auth()->user()->isSeeker()) {
                return $this->seeker_unread_count;
            } elseif (auth()->user()->isEmployer()) {
                return $this->employer_unread_count;
            }
        }
        return 0;
    }

    /**
     * Get the other participant's name
     */
    public function getOtherParticipantAttribute()
    {
        if (auth()->check()) {
            $user = auth()->user();
            
            if ($user->isAdmin()) {
                // Admin viewing: show seeker or employer name
                if ($this->seeker_id) {
                    return $this->seeker->user->name ?? 'Unknown User';
                } elseif ($this->employer_id) {
                    return $this->employer->company_name ?? 'Unknown Company';
                }
            } elseif ($user->isSeeker()) {
                // Seeker viewing: show employer or admin
                if ($this->admin_id) {
                    return 'Admin - ' . ($this->admin->name ?? 'System Admin');
                }
                return $this->employer->company_name ?? 'Unknown Company';
            } elseif ($user->isEmployer()) {
                // Employer viewing: show seeker or admin
                if ($this->admin_id) {
                    return 'Admin - ' . ($this->admin->name ?? 'System Admin');
                }
                return $this->seeker->user->name ?? 'Unknown User';
            }
        }
        return 'Unknown';
    }

    /**
     * Get the other participant's avatar
     */
    public function getOtherParticipantAvatarAttribute()
    {
        if (auth()->check()) {
            $user = auth()->user();
            
            if ($user->isAdmin()) {
                // Admin viewing
                if ($this->seeker_id && $this->seeker) {
                    return $this->seeker->user->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($this->seeker->user->name ?? 'User') . '&background=4F46E5&color=fff';
                } elseif ($this->employer_id && $this->employer) {
                    if ($this->employer->company_logo) {
                        return \Storage::url($this->employer->company_logo);
                    }
                    return 'https://ui-avatars.com/api/?name=' . urlencode($this->employer->company_name ?? 'Company') . '&background=4F46E5&color=fff';
                }
            } elseif ($user->isSeeker()) {
                // Seeker viewing
                if ($this->admin_id && $this->admin) {
                    return $this->admin->avatar_url ?? 'https://ui-avatars.com/api/?name=Admin&background=9333EA&color=fff';
                }
                if ($this->employer && $this->employer->company_logo) {
                    return \Storage::url($this->employer->company_logo);
                }
                return 'https://ui-avatars.com/api/?name=' . urlencode($this->employer->company_name ?? 'Company') . '&background=4F46E5&color=fff';
            } elseif ($user->isEmployer()) {
                // Employer viewing
                if ($this->admin_id && $this->admin) {
                    return $this->admin->avatar_url ?? 'https://ui-avatars.com/api/?name=Admin&background=9333EA&color=fff';
                }
                return $this->seeker->user->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($this->seeker->user->name ?? 'User') . '&background=4F46E5&color=fff';
            }
        }
        return 'https://ui-avatars.com/api/?name=U&background=4F46E5&color=fff';
    }

    /**
     * Mark conversation as read for current user
     */
    public function markAsRead()
    {
        if (auth()->check()) {
            $user = auth()->user();
            if ($user->isAdmin()) {
                $this->update(['admin_unread_count' => 0]);
            } elseif ($user->isSeeker()) {
                $this->update(['seeker_unread_count' => 0]);
            } elseif ($user->isEmployer()) {
                $this->update(['employer_unread_count' => 0]);
            }
        }
    }

    /**
     * Increment unread count for recipient
     */
    public function incrementUnreadCount($recipientType)
    {
        if ($recipientType === 'seeker') {
            $this->increment('seeker_unread_count');
        } elseif ($recipientType === 'employer') {
            $this->increment('employer_unread_count');
        } elseif ($recipientType === 'admin') {
            $this->increment('admin_unread_count');
        }
    }

    /**
     * Check if user is participant
     */
    public function isParticipant($user)
    {
        if ($user->isAdmin()) {
            return $this->admin_id === $user->id;
        } elseif ($user->isSeeker()) {
            return $this->seeker_id === $user->seeker->id;
        } elseif ($user->isEmployer()) {
            return $this->employer_id === $user->employer->id;
        }
        return false;
    }

    /**
     * Scope to get conversations for a seeker
     */
    public function scopeForSeeker($query, $seekerId)
    {
        return $query->where('seeker_id', $seekerId);
    }

    /**
     * Scope to get conversations for an employer
     */
    public function scopeForEmployer($query, $employerId)
    {
        return $query->where('employer_id', $employerId);
    }

    /**
     * Scope to get unread conversations
     */
    public function scopeUnread($query)
    {
        if (auth()->check()) {
            $user = auth()->user();
            if ($user->isAdmin()) {
                return $query->where('admin_unread_count', '>', 0);
            } elseif ($user->isSeeker()) {
                return $query->where('seeker_unread_count', '>', 0);
            } elseif ($user->isEmployer()) {
                return $query->where('employer_unread_count', '>', 0);
            }
        }
        return $query;
    }

    /**
     * Scope to get active (non-archived) conversations
     */
    public function scopeActive($query)
    {
        return $query->where('is_archived', false);
    }

    /**
     * Scope to get conversations for an admin
     */
    public function scopeForAdmin($query, $adminId)
    {
        return $query->where('admin_id', $adminId);
    }

    /**
     * Check if conversation involves admin
     */
    public function hasAdmin()
    {
        return !is_null($this->admin_id);
    }

    /**
     * Get the other participant's profile URL
     */
    public function getOtherParticipantProfileUrlAttribute()
    {
        if (auth()->check()) {
            $user = auth()->user();
            
            if ($user->isAdmin()) {
                // Admin viewing: link to seeker or employer profile
                if ($this->seeker_id && $this->seeker) {
                    return route('seekers.show', $this->seeker);
                } elseif ($this->employer_id && $this->employer) {
                    return route('employers.show', $this->employer->slug);
                }
            } elseif ($user->isSeeker()) {
                // Seeker viewing: no link to admin, but link to employer
                if (!$this->admin_id && $this->employer_id && $this->employer) {
                    return route('employers.show', $this->employer->slug);
                }
            } elseif ($user->isEmployer()) {
                // Employer viewing: no link to admin, but link to seeker
                if (!$this->admin_id && $this->seeker_id && $this->seeker) {
                    return route('seekers.show', $this->seeker);
                }
            }
        }
        return null;
    }
}
