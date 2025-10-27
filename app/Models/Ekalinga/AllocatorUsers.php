<?php

namespace App\Models\Ekalinga;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AllocatorUsers extends Model
{
    use HasFactory;

    protected $connection = 'e_kalinga';
    protected $table = 'allocator_users';
}
