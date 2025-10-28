<?php

namespace App\Models\Ekalinga;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ConsolidatedTransaction extends Model
{
    use HasFactory;

    protected $connection = 'e_kalinga';
    protected $table = 'consolidated_transaction';

    protected $fillable = [
        'office_id',
        'total_budget',
        'budget',
        'beneficiary_id',
        'budget_received',
        'status',
        'remarks',
    ];

    protected $casts = [
        'total_budget' => 'decimal:2',
        'budget' => 'decimal:2',
        'budget_received' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function scopeForOffice($query, $officeId)
    {
        return $query->where('office_id', $officeId);
    }

}
