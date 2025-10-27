<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BudgetRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'office_id',
        'requested_amount',
        'purpose',
        'description',
        'status',
    ];

    protected $casts = [
        'requested_amount' => 'decimal:2',
    ];

    /**
     * Get the status badge attribute
     */
    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'approved' => 'bg-success text-white',
            'rejected' => 'bg-danger text-white',
            'pending' => 'bg-warning text-white',
            default => 'bg-secondary text-white',
        };
    }

    /**
     * Get the formatted requested amount
     */
    public function getFormattedAmountAttribute()
    {
        return '₱' . number_format($this->requested_amount, 2);
    }

    /**
     * Scope for pending requests
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for approved requests
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope for rejected requests
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }
}
