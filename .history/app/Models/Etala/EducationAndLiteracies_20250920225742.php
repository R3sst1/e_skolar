<?php

namespace App\Models\Etala;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EducationAndLiteracies extends Model
{
    use HasFactory;

    protected $connection = 'e_tala';
   protected $table = 'education_and_literacies';

}
