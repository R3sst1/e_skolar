<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Scholar extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'institution',
        'barangay',
        'course',
        'year_level',
        'status', // active, graduated, discontinued
        'category', // Student, Master Degree, Graduate
        'start_date',
        'end_date',
    ];

    protected $dates = [
        'start_date',
        'end_date'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function renewals()
    {
        return $this->hasMany(ScholarRenewal::class);
    }

    public function performance()
    {
        return $this->hasMany(ScholarPerformance::class);
    }

    public function feedback()
    {
        return $this->hasMany(ScholarFeedback::class);
    }

    public function disbursementBatchStudents()
    {
        return $this->hasMany(DisbursementBatchStudent::class, 'student_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeByInstitution($query, $institution)
    {
        return $query->where('institution', $institution);
    }

    public function scopeByBarangay($query, $barangay)
    {
        return $query->where('barangay', $barangay);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }
} 