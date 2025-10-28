<?php

namespace App\Models\Etala;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ValidBenificiaries extends Model
{
    use HasFactory;

    protected $connection = 'e_tala';
    protected $table = 'val_benificiaries';

    protected $fillable = [
        'residents_id',
        'benificiary_id',
        'civilregistry_id',
        'last_name',
        'first_name',
        'middle_name',
        'full_name',
        'sex',
        'date_of_birth',
        'age',
        'marital_status',
        'address',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'age' => 'integer',
    ];

    /**
     * Check if residents are validated by matching names
     * Returns array of demographic IDs that match validated beneficiaries
     */
    public static function getValidatedDemographicIds($demographics)
    {
        $validatedIds = [];
        
        try {
            // Query val_benificiaries table from E-Kalinga
            $beneficiaries = self::all();
            
            foreach ($demographics as $demographic) {
                // Create comparable resident names (lowercase, trimmed)
                $residentFirstName = strtolower(trim($demographic->first_name ?? ''));
                $residentLastName = strtolower(trim($demographic->last_name ?? ''));
                
                // Check against all beneficiaries
                foreach ($beneficiaries as $beneficiary) {
                    // Create comparable beneficiary names (lowercase, trimmed)
                    $benefFirstName = strtolower(trim($beneficiary->first_name ?? ''));
                    $benefLastName = strtolower(trim($beneficiary->last_name ?? ''));
                    
                    // Match if both first name AND last name match
                    if (!empty($residentFirstName) && !empty($residentLastName) &&
                        $benefFirstName === $residentFirstName && 
                        $benefLastName === $residentLastName) {
                        $validatedIds[] = $demographic->id;
                        break; // Found a match, no need to check other beneficiaries for this demographic
                    }
                }
            }
        } catch (\Exception $e) {
            \Log::error('Error in ValidBenificiaries::getValidatedDemographicIds (e_tala): ' . $e->getMessage());
        }
        
        return $validatedIds;
    }
}
