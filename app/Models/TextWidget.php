<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TextWidget extends Model
{
    use HasFactory;

    protected $fillable = ['key', 'image', 'title', 'content', 'active'];

    /** we cache the result to reuse the result it for the either the title or the content because they both execute the same DB query*/
    public static function getTitle(string $key):string
    {
        //cache for 10mins
        $widget = Cache::remember('text-widget-' . $key, 10*60, function () use ($key) {
            return self::where('key', $key)->where('active', 1)->first();
        });

        return $widget ? $widget->title : '';
    }

    public static function getContent(string $key):string | null
    {
        $widget = Cache::remember('text-widget-' . $key, 10*60, function () use ($key) {
            return self::where('key', $key)->where('active', 1)->first();
        });

        return $widget ? $widget->content : '';
    } 
}
