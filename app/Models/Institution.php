<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Institution extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'address',
        'contact_person',
        'contact_email',
        'contact_phone',
        'description',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the scholars for this institution
     */
    public function scholars()
    {
        return $this->hasMany(Scholar::class, 'institution', 'name');
    }

    /**
     * Get active scholars count
     */
    public function getActiveScholarsCountAttribute()
    {
        return $this->scholars()->where('status', 'active')->count();
    }

    /**
     * Get total scholars count
     */
    public function getTotalScholarsCountAttribute()
    {
        return $this->scholars()->count();
    }

    /**
     * Scope to get only active institutions
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get institutions by type
     */
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }
}
