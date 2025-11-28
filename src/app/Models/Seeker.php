<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Seeker extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'phone',
        'date_of_birth',
        'gender',
        'address',
        'city',
        'state',
        'country',
        'postal_code',
        'current_job_title',
        'bio',
        'skills',
        'education',
        'experience',
        'cv_path',
        'resume_path',
        'portfolio_url',
        'linkedin_url',
        'github_url',
        'expected_salary_min',
        'expected_salary_max',
        'preferred_location',
        'job_type_preference',
    ];

    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
            'skills' => 'array',
            'education' => 'array',
            'experience' => 'array',
            'expected_salary_min' => 'decimal:2',
            'expected_salary_max' => 'decimal:2',
        ];
    }

    /**
     * Relationships
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function applications()
    {
        return $this->hasMany(Application::class);
    }

    public function savedJobs()
    {
        return $this->hasMany(SavedJob::class);
    }

    public function conversations()
    {
        return $this->hasMany(Conversation::class);
    }

    /**
     * Helper methods
     */
    public function getFullAddressAttribute(): string
    {
        $parts = array_filter([
            $this->address,
            $this->city,
            $this->state,
            $this->postal_code,
            $this->country,
        ]);

        return implode(', ', $parts);
    }

    public function hasAppliedToJob(int $jobId): bool
    {
        return $this->applications()->where('job_id', $jobId)->exists();
    }

    public function hasSavedJob(int $jobId): bool
    {
        return $this->savedJobs()->where('job_id', $jobId)->exists();
    }
}

