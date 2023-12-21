<?php

namespace App\Models;

class Post extends Home
{
    protected $fillable = [
        'title',
        'location',
        'description',
        'filename',
        'likes',
        'status',
        'type',
        'html',
        'user_id',
        'views',
        'category_id',
        'image'
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s'
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id')
            ->select('id', 'name', 'profile_image');
    }

    public function category()
    {
        return $this->hasOne(Category::class,'id','category_id')
        ->select('id', 'name');
    }

    public function getLikeStatusAttribute($val)
    {
        return Likes::where('ip', request()->ip())
            ->where('post_id', $val)
            ->exists();
    }
}
