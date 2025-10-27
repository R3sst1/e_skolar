<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemRequest extends Model
{
    use HasFactory;

    protected $table = 'requests';

    protected $fillable = [
        'type',
        'budget_allocation_id',
        'office_id',
        'constituent_id',
        'amount',
        'description',
        'status',
    ];
}
