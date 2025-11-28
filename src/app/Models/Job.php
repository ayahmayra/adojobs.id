<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Job extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'job_postings';

    protected $fillable = [
        'employer_id',
        'category_id',
        'title',
        'slug',
        'description',
        'requirements',
        'responsibilities',
        'benefits',
        'job_type',
        'work_mode',
        'location',
        'city',
        'state',
        'country',
        'salary_min',
        'salary_max',
        'salary_period',
        'salary_negotiable',
        'salary_currency',
        'experience_level',
        'experience_years_min',
        'experience_years_max',
        'education_level',
        'required_skills',
        'preferred_skills',
        'vacancies',
        'application_deadline',
        'application_email',
        'application_url',
        'status',
        'is_featured',
        'published_at',
        'views_count',
        'applications_count',
    ];

    protected function casts(): array
    {
        return [
            'salary_min' => 'decimal:2',
            'salary_max' => 'decimal:2',
            'salary_negotiable' => 'boolean',
            'required_skills' => 'array',
            'preferred_skills' => 'array',
            'vacancies' => 'integer',
            'application_deadline' => 'date',
            'is_featured' => 'boolean',
            'published_at' => 'datetime',
            'views_count' => 'integer',
            'applications_count' => 'integer',
        ];
    }

    /**
     * Boot method to auto-generate slug
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($job) {
            if (empty($job->slug)) {
                $job->slug = Str::slug($job->title) . '-' . Str::random(6);
            }
        });
    }

    /**
     * Relationships
     */
    public function employer()
    {
        return $this->belongsTo(Employer::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function applications()
    {
        return $this->hasMany(Application::class);
    }

    public function savedByUsers()
    {
        return $this->hasMany(SavedJob::class);
    }

    /**
     * Scopes
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published')->where('published_at', '<=', now());
    }

    public function scopeActive($query)
    {
        return $query->published()->where('application_deadline', '>=', now());
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeByCity($query, string $city)
    {
        return $query->where('city', 'like', "%{$city}%");
    }

    public function scopeByJobType($query, string $type)
    {
        return $query->where('job_type', $type);
    }

    public function scopeByWorkMode($query, string $mode)
    {
        return $query->where('work_mode', $mode);
    }

    /**
     * Helper methods
     */
    public function isExpired(): bool
    {
        return $this->application_deadline && $this->application_deadline->isPast();
    }

    public function isActive(): bool
    {
        return $this->status === 'published' && !$this->isExpired();
    }

    public function getSalaryRangeAttribute(): ?string
    {
        if (!$this->salary_min && !$this->salary_max) {
            return $this->salary_negotiable ? 'Negotiable' : null;
        }

        $currency = $this->salary_currency;
        $period = ucfirst($this->salary_period);

        if ($this->salary_min && $this->salary_max) {
            return "{$currency} " . number_format($this->salary_min, 0) . " - " . 
                   number_format($this->salary_max, 0) . " / {$period}";
        }

        if ($this->salary_min) {
            return "{$currency} " . number_format($this->salary_min, 0) . "+ / {$period}";
        }

        return "{$currency} " . number_format($this->salary_max, 0) . " / {$period}";
    }

    public function incrementViews(): void
    {
        $this->increment('views_count');
    }
}

