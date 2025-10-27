<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ScholarPerformance extends Model
{
    use HasFactory;

    protected $table = 'scholar_performance';

    protected $fillable = [
        'scholar_id',
        'semester',
        'school_year',
        'gwa',
        'units_enrolled',
        'units_completed',
        'units_failed',
        'subjects_enrolled',
        'subjects_passed',
        'subjects_failed',
        'subjects_dropped',
        'academic_remarks',
        'academic_status',
        'meets_retention_requirements',
        'submitted_at',
        'reviewed_at',
        'reviewed_by',
    ];

    protected $casts = [
        'gwa' => 'decimal:2',
        'units_enrolled' => 'integer',
        'units_completed' => 'integer',
        'units_failed' => 'integer',
        'subjects_enrolled' => 'integer',
        'subjects_passed' => 'integer',
        'subjects_failed' => 'integer',
        'subjects_dropped' => 'integer',
        'meets_retention_requirements' => 'boolean',
        'submitted_at' => 'date',
        'reviewed_at' => 'date',
    ];

    // Relationships
    public function scholar(): BelongsTo
    {
        return $this->belongsTo(Scholar::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    // Helper methods
    public function isGoodStanding(): bool
    {
        return $this->academic_status === 'good';
    }

    public function isOnWarning(): bool
    {
        return $this->academic_status === 'warning';
    }

    public function isOnProbation(): bool
    {
        return $this->academic_status === 'probation';
    }

    public function getCompletionRate(): float
    {
        if ($this->units_enrolled === 0) {
            return 0;
        }
        return round(($this->units_completed / $this->units_enrolled) * 100, 2);
    }

    public function getPassRate(): float
    {
        if ($this->subjects_enrolled === 0) {
            return 0;
        }
        return round(($this->subjects_passed / $this->subjects_enrolled) * 100, 2);
    }

    public function getFailedRate(): float
    {
        if ($this->subjects_enrolled === 0) {
            return 0;
        }
        return round(($this->subjects_failed / $this->subjects_enrolled) * 100, 2);
    }

    public function getUnitsRemaining(): int
    {
        return $this->units_enrolled - $this->units_completed;
    }

    public function meetsGWARequirement(): bool
    {
        $minimumGWA = \App\Models\SystemSetting::getValue('minimum_gwa_for_retention', 2.5);
        return $this->gwa <= $minimumGWA;
    }

    public function meetsUnitsRequirement(): bool
    {
        $minimumUnits = \App\Models\SystemSetting::getValue('minimum_units_per_semester', 12);
        return $this->units_completed >= $minimumUnits;
    }

    public function meetsNoFailedSubjectsRequirement(): bool
    {
        return $this->subjects_failed === 0;
    }

    public function calculateAcademicStatus(): string
    {
        $minimumGWA = \App\Models\SystemSetting::getValue('minimum_gwa_for_retention', 2.5);
        $minimumUnits = \App\Models\SystemSetting::getValue('minimum_units_per_semester', 12);

        // Check if meets all requirements
        if ($this->gwa <= $minimumGWA && $this->units_completed >= $minimumUnits && $this->subjects_failed === 0) {
            return 'good';
        }

        // Check if on probation (multiple failures)
        if ($this->gwa > $minimumGWA + 0.5 || $this->subjects_failed > 2) {
            return 'probation';
        }

        // Warning status
        return 'warning';
    }

    public function getStatusBadgeClass(): string
    {
        return match($this->academic_status) {
            'good' => 'bg-success',
            'warning' => 'bg-warning',
            'probation' => 'bg-danger',
            default => 'bg-secondary'
        };
    }

    public function getStatusText(): string
    {
        return match($this->academic_status) {
            'good' => 'Good Standing',
            'warning' => 'Academic Warning',
            'probation' => 'Academic Probation',
            default => 'Unknown'
        };
    }

    // Scopes for filtering
    public function scopeBySemester($query, $semester)
    {
        return $query->where('semester', $semester);
    }

    public function scopeBySchoolYear($query, $schoolYear)
    {
        return $query->where('school_year', $schoolYear);
    }

    public function scopeByAcademicStatus($query, $status)
    {
        return $query->where('academic_status', $status);
    }

    public function scopeMeetsRequirements($query)
    {
        return $query->where('meets_retention_requirements', true);
    }

    public function scopeNeedsAttention($query)
    {
        return $query->whereIn('academic_status', ['warning', 'probation']);
    }
}
