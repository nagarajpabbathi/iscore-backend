<?php

namespace App\Models;

class Likes extends Home
{
    protected $fillable = [
        'post_id',
        'ip'
    ];
}
