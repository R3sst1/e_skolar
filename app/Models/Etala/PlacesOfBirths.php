<?php

namespace App\Models\Etala;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlacesOfBirths extends Model
{
    use HasFactory;

    protected $connection = 'e_tala';
    protected $table = 'places_of_births';

    protected $fillable = [
        'demographic_characteristics_id',
        'place_of_birth',
        'municipality_of_birth',
        'province_of_birth',
    ];

    // Relationships
    public function demographicCharacteristic(): BelongsTo
    {
        return $this->belongsTo(DemographicIdentifications::class, 'demographic_characteristics_id');
    }

    // Accessors
    public function getFullPlaceOfBirthAttribute()
    {
        $place = [];
        
        if ($this->place_of_birth) {
            $place[] = $this->place_of_birth;
        }
        
        if ($this->municipality_of_birth) {
            $place[] = $this->municipality_of_birth;
        }
        
        if ($this->province_of_birth) {
            $place[] = $this->province_of_birth;
        }
        
        return implode(', ', $place);
    }
}
