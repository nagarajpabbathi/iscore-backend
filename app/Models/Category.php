<?php

namespace App\Models;

class Category extends Home
{
    protected $fillable = [
        'name',
        'status',
        'likes',
        'views',
        'img'
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d'
    ];
}
