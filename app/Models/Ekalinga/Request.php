<?php

namespace App\Models\Ekalinga;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Request extends Model
{
    use HasFactory;

    protected $connection = 'e_kalinga';
    protected $table = 'requests';

    protected $fillable = [
        'constituent_id',
        'office_id',
        'item_name',
        'quantity',
        'item_cost',
        'requested_amount',
        'description',
        'purpose',
        'barangay',
        'status',
        'active',
        'disbursement_batch_id',
    ];

    protected $casts = [
        'active' => 'boolean',
        'quantity' => 'integer',
        'item_cost' => 'decimal:2',
        'requested_amount' => 'decimal:2',
    ];

    public function amountToCharge(): float
    {
        $qty = max(1, (int) ($this->quantity ?? 1));
        $qtyTotal = (float) ($this->item_cost ?? 0) * $qty;
        return (float) ($this->requested_amount ?? $qtyTotal);
    }
}
