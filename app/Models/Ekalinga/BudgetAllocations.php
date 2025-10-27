<?php

namespace App\Models\Ekalinga;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BudgetAllocations extends Model
{
    use HasFactory;

     protected $connection = 'e_kalinga';
     protected $table = 'budget_allocations';

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
}
