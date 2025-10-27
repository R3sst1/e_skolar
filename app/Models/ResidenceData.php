<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResidenceData extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'middle_name',
        'contact_number',
        'email',
        'barangay',
        'age',
        'user_id',
        'account_created',
        'account_created_at',
    ];

    protected $casts = [
        'account_created' => 'boolean',
        'account_created_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getFullNameAttribute()
    {
        $name = $this->first_name;
        
        if ($this->middle_name) {
            $name .= ' ' . $this->middle_name;
        }
        
        $name .= ' ' . $this->last_name;
        
        return trim($name);
    }

    public function getAccountStatusAttribute()
    {
        return $this->account_created ? 'Created' : 'Pending';
    }

    public function getAccountStatusBadgeAttribute()
    {
        return $this->account_created 
            ? 'bg-success text-white' 
            : 'bg-warning text-white';
    }
}
