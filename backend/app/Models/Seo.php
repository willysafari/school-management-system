<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Seo extends Model
{
    //protected
    protected $fillable =[
        'post_id',
        'meta_title',
        'meta_description',
        'meta_keywords'
    ];
}
