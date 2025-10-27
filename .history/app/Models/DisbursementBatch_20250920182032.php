<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DisbursementBatch extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tbl_disbursement_batches';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'reference_number',
        'scholarship_program_id',
        'status',
        'total_amount',
        'budget_allocated',
        'remarks',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'total_amount' => 'decimal:2',
        'budget_allocated' => 'decimal:2',
    ];

    /**
     * Get the disbursement batch students for this batch.
     */
    public function disbursementBatchStudents(): HasMany
    {
        return $this->hasMany(DisbursementBatchStudent::class, 'disbursement_batch_id');
    }

    /**
     * Get the scholarship program for this batch.
     */
    public function scholarshipProgram(): BelongsTo
    {
        return $this->belongsTo(ScholarshipProgram::class, 'scholarship_program_id');
    }

    /**
     * Generate a unique reference number for the disbursement batch.
     */
    public static function generateReferenceNumber(): string
    {
        $year = now()->year;
        $lastBatch = self::whereYear('created_at', $year)
            ->orderBy('id', 'desc')
            ->first();

        $nextNumber = $lastBatch ? 
            (int) substr($lastBatch->reference_number, -4) + 1 : 1;

        return sprintf('BATCH-%d-%04d', $year, $nextNumber);
    }

    /**
     * Boot method to auto-generate reference number.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->reference_number)) {
                $model->reference_number = self::generateReferenceNumber();
            }
        });
    }
}