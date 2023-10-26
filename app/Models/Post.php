<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\ImageTrait;

class Post extends Model
{
    use ImageTrait;
    use HasFactory;

    protected $fillable = ['title', 'slug', 'thumbnail', 'body', 'active', 'published_at', 'user_id', 'meta_title', 'meta_description'];

    public static function boot()
    {
        static::creating(function ($model) {
            // blah blah
        });

        static::updating(function ($model) {
            /** delete old thumbnail if changed */
            $image = $model->getOriginal('thumbnail');
            if ($model->isDirty('thumbnail') && ($image !== null)) {
                self::deleteImage(disk: 'storage', folder: 'public', image: $image);
            }
        });

        static::deleting(function ($model) {
            /** delete thumbnail */
            $folder = 'public';
            $image = $model->thumbnail;
            self::deleteImage(disk: 'storage', folder: $folder, image: $image);
        });
        
        parent::boot();
    }

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

    public function comments() {
        return $this->hasMany(Comment::class);
    }

    public function readTime() : Attribute {
        return new Attribute(get: fn() => (int) ceil(str_word_count(strip_tags($this->body)) / 200));   
    }
}
