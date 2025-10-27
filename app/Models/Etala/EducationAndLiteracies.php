<?php

namespace App\Models\Etala;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EducationAndLiteracies extends Model
{
    use HasFactory;

    protected $connection = 'e_tala';
    protected $table = 'education_and_literacies';

    protected $fillable = [
        'demographic_characteristics_id',
        'geographic_identifications_id',
        'basic_literacy',
        'grade_year_id',
        'current_school_attendance',
        'type_of_school',
        'current_grade_year_id',
        'reason_not_attending_school',
        'other_reason_not_attending_school',
        'graduate_of_TVL',
        'is_currently_attending_TVET_skills_dev',
        'status',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'graduate_of_TVL' => 'boolean',
        'is_currently_attending_TVET_skills_dev' => 'boolean',
        'current_school_attendance' => 'boolean',
    ];

    // Relationships
    public function demographicCharacteristic(): BelongsTo
    {
        return $this->belongsTo(DemographicIdentifications::class, 'demographic_characteristics_id');
    }

    public function geographicIdentification(): BelongsTo
    {
        return $this->belongsTo(GeographicIdentifications::class, 'geographic_identifications_id');
    }

    public function gradeYear(): BelongsTo
    {
        return $this->belongsTo(GradeYears::class, 'grade_year_id');
    }

    public function currentGradeYear(): BelongsTo
    {
        return $this->belongsTo(GradeYears::class, 'current_grade_year_id');
    }
}
