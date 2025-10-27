<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class AssistanceRequest extends Model
{
    use HasFactory;

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

    public function office(): BelongsTo
    {
        return $this->belongsTo(Office::class);
    }

    public function constituent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'constituent_id');
    }

    public function disbursementBatch(): BelongsTo
    {
        return $this->belongsTo(DisbursementBatch::class, 'disbursement_batch_id');
    }

    public function amountToCharge(): float
    {
        $quantityTotal = ($this->item_cost ?? 0) * max(1, (int) $this->quantity);
        return (float) ($this->requested_amount ?? $quantityTotal);
    }
}


