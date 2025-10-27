<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScholarFeedback extends Model
{
    use HasFactory;

    protected $fillable = [
        'scholar_id',
        'category',
        'rating',
        'title',
        'message',
        'anonymous',
        'status',
        'admin_response',
        'reviewed_at',
        'reviewed_by',
    ];

    protected $casts = [
        'anonymous' => 'boolean',
        'reviewed_at' => 'datetime',
    ];

    public function scholar()
    {
        return $this->belongsTo(Scholar::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function getCategoryLabelAttribute()
    {
        return ucfirst($this->category);
    }

    public function getStatusLabelAttribute()
    {
        return ucfirst(str_replace('_', ' ', $this->status));
    }

    public function getRatingStarsAttribute()
    {
        return str_repeat('★', $this->rating) . str_repeat('☆', 5 - $this->rating);
    }

    public function isReviewed()
    {
        return $this->status === 'reviewed' || $this->status === 'resolved';
    }

    public function isAnonymous()
    {
        return $this->anonymous;
    }
}
