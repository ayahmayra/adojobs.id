<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_active',
        'avatar',
        'resume_slug',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'is_tester' => 'boolean',
            'tester_welcomed_at' => 'datetime',
        ];
    }

    /**
     * Role checking methods
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isEmployer(): bool
    {
        return $this->role === 'employer';
    }

    public function isSeeker(): bool
    {
        return $this->role === 'seeker';
    }

    /**
     * Relationships
     */
    public function seeker()
    {
        return $this->hasOne(Seeker::class);
    }

    public function employer()
    {
        return $this->hasOne(Employer::class);
    }

    /**
     * Scopes
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByRole($query, string $role)
    {
        return $query->where('role', $role);
    }

    /**
     * Avatar Methods
     */
    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar && file_exists(public_path('storage/' . $this->avatar))) {
            return asset('storage/' . $this->avatar);
        }

        // Return UI Avatars as placeholder
        return $this->getPlaceholderAvatar();
    }

    public function getPlaceholderAvatar(): string
    {
        $name = urlencode($this->name);
        $initials = $this->getInitials();
        
        // Using UI Avatars API for placeholder
        return "https://ui-avatars.com/api/?name={$name}&color=4F46E5&background=EEF2FF&bold=true&size=128";
    }

    public function getInitials(): string
    {
        $words = explode(' ', $this->name);
        if (count($words) >= 2) {
            return strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1));
        }
        return strtoupper(substr($this->name, 0, 2));
    }

    /**
     * Resume Slug Methods
     */
    public static function generateResumeSlug(string $email): string
    {
        // Get username part from email (before @)
        $username = explode('@', $email)[0];
        
        // Clean and slugify
        $baseSlug = \Illuminate\Support\Str::slug($username);
        
        // Check if slug exists
        $slug = $baseSlug;
        $counter = 1;
        
        while (self::where('resume_slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }
        
        return $slug;
    }

    public function getResumeUrlAttribute(): string
    {
        return $this->resume_slug ? route('resume.show', $this->resume_slug) : '#';
    }

    public function hasPublicResume(): bool
    {
        return !empty($this->resume_slug) && $this->isSeeker() && $this->seeker;
    }

    /**
     * Get counts of all related data that will be deleted
     * Used for cascade delete warnings
     */
    public function getRelatedDataCounts(): array
    {
        $counts = [];
        
        if ($this->isSeeker() && $this->seeker) {
            $applications = $this->seeker->applications()->count();
            $savedJobs = $this->seeker->savedJobs()->count();
            $conversations = $this->seeker->conversations()->count();
            
            if ($applications > 0) $counts['applications'] = $applications;
            if ($savedJobs > 0) $counts['saved_jobs'] = $savedJobs;
            if ($conversations > 0) $counts['conversations'] = $conversations;
        }
        
        if ($this->isEmployer() && $this->employer) {
            $jobs = $this->employer->jobs()->count();
            $conversations = $this->employer->conversations()->count();
            $savedCandidates = $this->employer->savedCandidates()->count();
            
            // Count applications to employer's jobs
            $receivedApplications = \App\Models\Application::whereHas('job', function($q) {
                $q->where('employer_id', $this->employer->id);
            })->count();
            
            if ($jobs > 0) $counts['job_postings'] = $jobs;
            if ($receivedApplications > 0) $counts['received_applications'] = $receivedApplications;
            if ($conversations > 0) $counts['conversations'] = $conversations;
            if ($savedCandidates > 0) $counts['saved_candidates'] = $savedCandidates;
        }
        
        return $counts;
    }

    /**
     * Check if user has any related data
     */
    public function hasRelatedData(): bool
    {
        return !empty($this->getRelatedDataCounts());
    }

    /**
     * Tester Mode Methods
     */
    public function isTester(): bool
    {
        return (bool) $this->is_tester;
    }

    public function needsTesterWelcome(): bool
    {
        return $this->is_tester && is_null($this->tester_welcomed_at);
    }

    public function markTesterWelcomed(): void
    {
        $this->update(['tester_welcomed_at' => now()]);
    }

    /**
     * Feedback relationship
     */
    public function feedbacks()
    {
        return $this->hasMany(Feedback::class);
    }
}
