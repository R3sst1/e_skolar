<?php

namespace App\Models\Etala;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GradeYears extends Model
{
    use HasFactory;

     protected $connection = 'e_tala';
    protected $table = 'grade_years';

}
