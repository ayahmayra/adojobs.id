<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Feedback extends Model
{
    protected $fillable = [
        'user_id',
        'category',
        'title',
        'description',
        'screenshot',
        'status',
        'admin_notes',
    ];

    const CATEGORIES = [
        'UI' => 'User Interface',
        'UX' => 'User Experience',
        'Functional' => 'Fungsional',
        'Typo' => 'Typo',
        'Performance' => 'Performa',
        'Security' => 'Keamanan',
        'Other' => 'Lainnya',
    ];

    const STATUS_NEW = 'new';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_RESOLVED = 'resolved';
    const STATUS_CLOSED = 'closed';

    /**
     * Get the user who submitted this feedback
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get status badge color
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            self::STATUS_NEW => 'blue',
            self::STATUS_IN_PROGRESS => 'yellow',
            self::STATUS_RESOLVED => 'green',
            self::STATUS_CLOSED => 'gray',
            default => 'gray',
        };
    }

    /**
     * Get status label
     */
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            self::STATUS_NEW => 'Baru',
            self::STATUS_IN_PROGRESS => 'Dalam Proses',
            self::STATUS_RESOLVED => 'Selesai',
            self::STATUS_CLOSED => 'Ditutup',
            default => $this->status,
        };
    }
}
