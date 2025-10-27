<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AllocationLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'office_id',
        'allocated_by',
        'disbursement_batch_id',
        'transaction_type',
        'amount',
        'description',
        'reference_number',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function allocatedBy()
    {
        return $this->belongsTo(User::class, 'allocated_by');
    }

    public function disbursementBatch()
    {
        return $this->belongsTo(DisbursementBatch::class, 'disbursement_batch_id');
    }

    // Scopes
    public function scopeDisbursements($query)
    {
        return $query->where('transaction_type', 'disbursement');
    }

    public function scopeAllocations($query)
    {
        return $query->where('transaction_type', 'allocation');
    }

    public function scopeForOffice($query, $officeId = 6)
    {
        return $query->where('office_id', $officeId);
    }

    // Helper methods
    public function getFormattedAmountAttribute()
    {
        return '₱' . number_format($this->amount, 2);
    }

    public function getTransactionTypeBadgeAttribute()
    {
        $badges = [
            'allocation' => 'success',
            'disbursement' => 'primary',
            'adjustment' => 'warning',
        ];

        return $badges[$this->transaction_type] ?? 'secondary';
    }
}
