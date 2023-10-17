<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'slug', 'thumbnail', 'body', 'active', 'published_at', 'user_id', 'meta_title', 'meta_description'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function categories() {
        return $this->belongsToMany(Category::class);
    }

    public function getThumbnail() {
        if(str_starts_with($this->thumbnail, 'http')) {
            return $this->thumbnail;
        }
        return asset('/storage/'. $this->thumbnail);
    }

    public function votes() {
        return $this->hasMany(PostVote::class);
    }

    public function readTime() : Attribute {
        return new Attribute(get: fn() => (int) ceil(str_word_count(strip_tags($this->body)) / 200));   
    }
}
