<?php

namespace App\Models\Etala;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FamilyHeadRelationships extends Model
{
    use HasFactory;

     protected $connection = 'e_tala';
    protected $table = 'family_head_relationships';

}
