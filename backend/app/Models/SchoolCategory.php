<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolCategory extends Model
{
    //protected
    protected $fillable = [
        'name',
        'slug'
    ];
}
