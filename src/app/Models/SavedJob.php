<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SavedJob extends Model
{
    use HasFactory;

    protected $fillable = [
        'seeker_id',
        'job_id',
        'notes',
    ];

    /**
     * Relationships
     */
    public function seeker()
    {
        return $this->belongsTo(Seeker::class);
    }

    public function job()
    {
        return $this->belongsTo(Job::class);
    }
}

