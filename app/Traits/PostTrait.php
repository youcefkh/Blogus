<?php

namespace App\Traits;

use App\Models\Post;
use App\Models\PostView;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

trait PostTrait
{

    public function countView(Request $request, Post $post)
    {
        $Key = 'key_' . $post->slug . '_' . $request->user()?->id;
        if (!Session::has($Key)) {
            $user = $request->user();

            PostView::create([
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'user_id' => $user?->id,
                'post_id' => $post->id,
            ]);
            Session::put($Key, 1);
        }
    }
}
