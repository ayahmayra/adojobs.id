<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Employer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'company_name',
        'slug',
        'company_slug',
        'company_description',
        'company_logo',
        'company_website',
        'company_size',
        'industry',
        'founded_year',
        'contact_person',
        'contact_phone',
        'contact_email',
        'address',
        'city',
        'state',
        'country',
        'postal_code',
        'linkedin_url',
        'twitter_url',
        'facebook_url',
        'is_verified',
        'verified_at',
    ];

    protected function casts(): array
    {
        return [
            'is_verified' => 'boolean',
            'verified_at' => 'datetime',
            'founded_year' => 'integer',
        ];
    }

    /**
     * Relationships
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function jobs()
    {
        return $this->hasMany(Job::class);
    }

    public function conversations()
    {
        return $this->hasMany(Conversation::class);
    }

    /**
     * Boot method to auto-generate slug
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($employer) {
            if (empty($employer->slug)) {
                $employer->slug = Str::slug($employer->company_name);
            }
            if (empty($employer->company_slug)) {
                $employer->company_slug = Str::slug($employer->company_name);
            }
        });

        static::updating(function ($employer) {
            if ($employer->isDirty('company_name') && empty($employer->slug)) {
                $employer->slug = Str::slug($employer->company_name);
            }
        });
    }

    /**
     * Scopes
     */
    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
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

    public function activeJobs()
    {
        return $this->jobs()->where('status', 'published')->where('application_deadline', '>=', now());
    }

    /**
     * Generate unique slug
     */
    public function generateUniqueSlug($slug = null)
    {
        if (empty($slug)) {
            $slug = Str::slug($this->company_name);
        }

        $originalSlug = $slug;
        $counter = 1;

        while (static::where('slug', $slug)->where('id', '!=', $this->id ?? 0)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    /**
     * Get public profile URL
     */
    public function getPublicProfileUrlAttribute()
    {
        return route('employers.show', $this->slug ?: $this->id);
    }
}

