<?php

namespace App\Models\Etala;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ValidatedBenefeciaries extends Model
{
    use HasFactory;

    protected $connection = 'e_tala';
    protected $table = 'val_beneficiaries';

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
     * Check if a resident exists in validated beneficiaries using hasWhere
     */
    public static function isResidentValidated($residentId)
    {
        return static::where('residents_id', $residentId)->exists();
    }

    /**
     * Get validation status for multiple residents using hasWhere
     */
    public static function getValidationStatusForResidents($residentIds)
    {
        return static::whereIn('residents_id', $residentIds)
            ->pluck('residents_id')
            ->toArray();
    }

    /**
     * Check validation using hasWhere relationship method
     */
    public static function hasWhereValidation($residentIds)
    {
        return static::whereIn('residents_id', $residentIds)
            ->pluck('residents_id')
            ->toArray();
    }
}
