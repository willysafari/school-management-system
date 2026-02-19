<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolPost extends Model
{
    // fillabl
    protected $fillable=[
        'user_id',
        'category_id',
        'title',
        'slug',
        'content',
        'excerpt',
        'thumbnail',
        'status',
        'published_at'
    ];


}
