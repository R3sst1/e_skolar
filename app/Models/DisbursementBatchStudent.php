<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DisbursementBatchStudent extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tbl_disbursement_batch_students';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'disbursement_batch_id',
        'application_id',
        'student_id',
        'status',
        'requested_amount',
        'actual_amount',
        'release_status',
        'released_at',
        'release_remarks',
        'remarks',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'requested_amount' => 'decimal:2',
        'actual_amount' => 'decimal:2',
        'released_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the disbursement batch that owns this student record.
     */
    public function disbursementBatch(): BelongsTo
    {
        return $this->belongsTo(DisbursementBatch::class, 'disbursement_batch_id');
    }

    /**
     * Get the application that owns this disbursement record.
     */
    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class, 'application_id');
    }

    /**
     * Get the scholar that owns this disbursement record.
     */
    public function scholar(): BelongsTo
    {
        return $this->belongsTo(Scholar::class, 'student_id');
    }

    /**
     * Check if the disbursement is released.
     */
    public function isReleased(): bool
    {
        return $this->release_status === 'released';
    }

    /**
     * Check if the disbursement is unreleased.
     */
    public function isUnreleased(): bool
    {
        return $this->release_status === 'unreleased';
    }

    /**
     * Get the release status badge class.
     */
    public function getReleaseStatusBadgeAttribute(): string
    {
        return match($this->release_status) {
            'released' => 'bg-success text-white',
            'unreleased' => 'bg-warning text-white',
            default => 'bg-slate-100 text-slate-600'
        };
    }

    /**
     * Scope for released disbursements.
     */
    public function scopeReleased($query)
    {
        return $query->where('release_status', 'released');
    }

    /**
     * Scope for unreleased disbursements.
     */
    public function scopeUnreleased($query)
    {
        return $query->where('release_status', 'unreleased');
    }

    /**
     * Get formatted released date with timezone.
     */
    public function getFormattedReleasedAtAttribute(): string
    {
        if (!$this->released_at) {
            return 'Not released';
        }
        
        return $this->released_at->setTimezone(config('app.timezone'))->format('M d, Y H:i');
    }

    /**
     * Get human readable time since release.
     */
    public function getTimeSinceReleaseAttribute(): string
    {
        if (!$this->released_at) {
            return 'Not released';
        }
        
        return $this->released_at->setTimezone(config('app.timezone'))->diffForHumans();
    }

    /**
     * Check if disbursement was released today.
     */
    public function wasReleasedToday(): bool
    {
        if (!$this->released_at) {
            return false;
        }
        
        return $this->released_at->setTimezone(config('app.timezone'))->isToday();
    }

    /**
     * Check if disbursement was released this week.
     */
    public function wasReleasedThisWeek(): bool
    {
        if (!$this->released_at) {
            return false;
        }
        
        return $this->released_at->setTimezone(config('app.timezone'))->isCurrentWeek();
    }
}