<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Application extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'application_number',
        'status',
        'school',
        'course',
        'year_level',
        'semester',
        'school_year',
        'gwa',
        'family_income',
        'reason_for_application',
        'grade_photo',
        'admin_remarks',
        'reviewed_at',
        'approved_at',
        'rejected_at',
        'scholarship_id',
    ];

    protected $casts = [
        'gwa' => 'decimal:2',
        'family_income' => 'decimal:2',
        'reviewed_at' => 'datetime',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function requirements()
    {
        return $this->hasMany(Requirement::class);
    }

    public function scholarship()
    {
        return $this->belongsTo(\App\Models\ScholarshipProgram::class);
    }

    /**
     * Get the disbursement batch students for this application.
     */
    public function disbursementBatchStudents(): HasMany
    {
        return $this->hasMany(DisbursementBatchStudent::class, 'application_id');
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isUnderReview()
    {
        return $this->status === 'under_review';
    }

    public function isApproved()
    {
        return $this->status === 'approved';
    }

    public function isRejected()
    {
        return $this->status === 'rejected';
    }

    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'pending' => 'bg-warning',
            'under_review' => 'bg-primary',
            'approved' => 'bg-success',
            'rejected' => 'bg-danger',
            default => 'bg-slate-100'
        };
    }

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($application) {
            // Generate unique application number: YYYY-MM-XXXXX
            $latest = static::whereYear('created_at', now()->year)
                ->whereMonth('created_at', now()->month)
                ->latest()
                ->first();

            $sequence = $latest ? intval(substr($latest->application_number, -5)) + 1 : 1;
            
            $application->application_number = sprintf(
                '%04d-%02d-%05d',
                now()->year,
                now()->month,
                $sequence
            );
        });
    }
}
