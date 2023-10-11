<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class TextWidget extends Model
{
    use HasFactory;

    protected $fillable = ['key', 'image', 'title', 'content', 'active'];

    /** we cache the result to reuse the result it for the either the title or the content because they both execute the same DB query*/
    public static function getTitle(string $key):string
    {
        $widget = Cache::get('text-widget-' . $key, function () use ($key) {
            return self::where('key', $key)->where('active', 1)->first();
        });

        return $widget ? $widget->title : '';
    }

    public static function getContent(string $key):string
    {
        $widget = Cache::get('text-widget-' . $key, function () use ($key) {
            return self::where('key', $key)->where('active', 1)->first();
        });

        return $widget ? $widget->content : '';
    } 
}
