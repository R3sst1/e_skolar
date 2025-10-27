<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScholarshipProgram extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tbl_scholarship_programs';

    protected $fillable = [
        'name',
        'description',
        'status',
        'image',
        'deadline',
        'type',
        'allocated_budget',
        'per_scholar_amount',
        'auto_close',
    ];

    public function disbursementBatches()
    {
        return $this->hasMany(DisbursementBatch::class, 'scholarship_program_id');
    }
} 