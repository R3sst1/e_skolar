<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BudgetAllocation extends Model
{
    use HasFactory;

    protected $table = 'budget_allocation';

    protected $fillable = [
        'master_budget_id',
        'office_id',
        'office_type',
        'allocated_by',
        'amount',
        'description',
        'remaining_amount',
        'used_amount',
        'status',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'remaining_amount' => 'decimal:2',
        'used_amount' => 'decimal:2',
    ];

    public function office(): BelongsTo
    {
        return $this->belongsTo(Office::class);
    }
}


