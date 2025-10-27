<?php

namespace App\Models\Ekalinga;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Offices extends Model
{
    use HasFactory;

    protected $connection = 'e_kalinga';
    protected $table = 'offices';

    protected $fillable = [
        'name',
        'description',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];
}
