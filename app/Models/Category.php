<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'slug'];

    public function posts()
    {
        return $this->belongsToMany(Post::class);
    }

    public function activePosts($limit = 3)
    {
        return $this->belongsToMany(Post::class)
            ->where('posts.active', 1)
            ->where('posts.published_at', '<=', Carbon::now())
            ->orderBy('posts.published_at', 'desc')
            ->limit($limit)
            ->get();
    }
}
