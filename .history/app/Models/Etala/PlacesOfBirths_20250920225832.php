<?php

namespace App\Models\Etala;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlacesOfBirths extends Model
{
    use HasFactory;

     protected $connection = 'e_tala';
    protected $table = 'places_of_births';

}
