<?php

namespace App\Models\Etala;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GeographicIdentifications extends Model
{
    use HasFactory;

    protected $connection = 'e_tala';
    protected $table = 'geographic_identifications';

    protected $fillable = [
        'region_id',
        'province_id',
        'city_id',
        'barangay_id',
        'enumeration_area_number',
        'building_serial_number',
        'housing_unit_serial_number',
        'household_serial_number',
        'household_number',
        'respondent_line_number',
        'household_head_id',
        'contact_number',
        'email_address',
        'latitude',
        'longitude',
        'floor_number',
        'house_number',
        'block_lot_number',
        'street_name',
        'subdivision_village_name',
        'sitio_purok_id',
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    // Relationships
    public function barangay(): BelongsTo
    {
        return $this->belongsTo(Barangays::class, 'barangay_id');
    }

    // Note: sitio_purok_id might reference a table without a model yet
    // For now, we'll handle purok data through subdivision_village_name

    public function demographicCharacteristics(): HasMany
    {
        return $this->hasMany(DemographicIdentifications::class, 'geographic_identifications_id');
    }

    public function educationAndLiteracies(): HasMany
    {
        return $this->hasMany(EducationAndLiteracies::class, 'geographic_identifications_id');
    }

    public function familyIncomes(): HasMany
    {
        return $this->hasMany(FamilyIncomes::class, 'geographic_identifications_id');
    }

    // Accessors
    public function getFullAddressAttribute()
    {
        $address = [];
        
        if ($this->house_number) {
            $address[] = $this->house_number;
        }
        
        if ($this->street_name) {
            $address[] = $this->street_name;
        }
        
        if ($this->subdivision_village_name) {
            $address[] = $this->subdivision_village_name;
        }
        
        if ($this->barangay) {
            $address[] = $this->barangay->name;
        }
        
        return implode(', ', $address);
    }
}
