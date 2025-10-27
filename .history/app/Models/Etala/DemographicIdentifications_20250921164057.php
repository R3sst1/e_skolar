<?php

namespace App\Models\Etala;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class DemographicIdentifications extends Model
{
    use HasFactory;

    protected $connection = 'e_tala';
    protected $table = 'demographic_characteristics';

    protected $fillable = [
        'geographic_identifications_id',
        'household_number',
        'line_number',
        'registry_number',
        'last_name',
        'first_name',
        'middle_name',
        'suffix',
        'full_name',
        'relationship_to_head',
        'nuclear_family_assignment',
        'relationship_to_head_nuclear_family',
        'sex',
        'date_of_birth',
        'age',
        'birth_registered_in_local_registry',
        'marital_status',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'birth_registered_in_local_registry' => 'boolean',
    ];

    // Relationships
    public function geographicIdentification(): BelongsTo
    {
        return $this->belongsTo(GeographicIdentifications::class, 'geographic_identifications_id');
    }

    public function educationAndLiteracy(): HasOne
    {
        return $this->hasOne(EducationAndLiteracies::class, 'demographic_characteristics_id');
    }

    public function familyIncome(): HasOne
    {
        return $this->hasOne(FamilyIncomes::class, 'demographic_characteristics_id');
    }

    public function placeOfBirth(): HasOne
    {
        return $this->hasOne(PlacesOfBirths::class, 'demographic_characteristics_id');
    }

    public function maritalStatus(): BelongsTo
    {
        return $this->belongsTo(MaritalStatuses::class, 'marital_status', 'id');
    }

    public function familyHeadRelationship(): BelongsTo
    {
        return $this->belongsTo(FamilyHeadRelationships::class, 'relationship_to_head', 'id');
    }

    public function nuclearFamilyRelationship(): BelongsTo
    {
        return $this->belongsTo(NuclearFamilyHeadRelationships::class, 'relationship_to_head_nuclear_family', 'id');
    }

    public function sex(): BelongsTo
    {
        return $this->belongsTo(Sexes::class, 'sex', 'code');
    }

    // Accessors
    public function getFullNameAttribute()
    {
        $name = $this->first_name;
        
        if ($this->middle_name) {
            $name .= ' ' . $this->middle_name;
        }
        
        $name .= ' ' . $this->last_name;
        
        if ($this->suffix) {
            $name .= ' ' . $this->suffix;
        }
        
        return trim($name);
    }

    public function getAgeAttribute($value)
    {
        if ($this->date_of_birth) {
            return $this->date_of_birth->age;
        }
        return $value;
    }
}
