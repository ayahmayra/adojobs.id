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
}
