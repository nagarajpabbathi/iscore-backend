<?php

namespace App\Models;

class Casino extends Home
{
    protected $fillable = [
        'banner_title',
        'title',
        'description',
        'rating',
        'url',
        'img',
        'status',
        'top_rated',
    ];
}
