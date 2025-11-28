<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SavedCandidate extends Model
{
    use HasFactory;

    protected $fillable = [
        'employer_id',
        'seeker_id',
        'notes',
    ];

    /**
     * Relationships
     */
    public function employer()
    {
        return $this->belongsTo(Employer::class);
    }

    public function seeker()
    {
        return $this->belongsTo(Seeker::class);
    }
}
