<?php

namespace App\Models\Etala;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barangays extends Model
{
    use HasFactory;

    protected $connection = 'e_tala';
    protected $table = 'barangays';

    protected $fillable = [
        'code',
        'name',
        'description',
        'province_id',
        'municipality_id',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
