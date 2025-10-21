<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Application extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'job_id',
        'seeker_id',
        'cover_letter',
        'cv_path',
        'additional_documents',
        'status',
        'employer_notes',
        'reviewed_at',
        'shortlisted_at',
        'interview_at',
        'hired_at',
        'rejected_at',
        'rejection_reason',
    ];

    protected function casts(): array
    {
        return [
            'additional_documents' => 'array',
            'reviewed_at' => 'datetime',
            'shortlisted_at' => 'datetime',
            'interview_at' => 'datetime',
            'hired_at' => 'datetime',
            'rejected_at' => 'datetime',
        ];
    }

    /**
     * Relationships
     */
    public function job()
    {
        return $this->belongsTo(Job::class);
    }

    public function seeker()
    {
        return $this->belongsTo(Seeker::class);
    }

    /**
     * Scopes
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeReviewed($query)
    {
        return $query->where('status', 'reviewed');
    }

    public function scopeShortlisted($query)
    {
        return $query->where('status', 'shortlisted');
    }

    public function scopeHired($query)
    {
        return $query->where('status', 'hired');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    /**
     * Helper methods
     */
    public function updateStatus(string $status, ?string $notes = null): void
    {
        $this->status = $status;
        $this->employer_notes = $notes;

        switch ($status) {
            case 'reviewed':
                $this->reviewed_at = now();
                break;
            case 'shortlisted':
                $this->shortlisted_at = now();
                break;
            case 'hired':
                $this->hired_at = now();
                break;
            case 'rejected':
                $this->rejected_at = now();
                $this->rejection_reason = $notes;
                break;
        }

        $this->save();
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isProcessed(): bool
    {
        return in_array($this->status, ['hired', 'rejected', 'withdrawn']);
    }
}

