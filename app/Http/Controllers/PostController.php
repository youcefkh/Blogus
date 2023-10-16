<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Post;
use App\Models\Category;
use App\Models\PostView;
use Illuminate\Http\Request;
use Illuminate\Cache\RateLimiting\Limit;

use function Laravel\Prompts\select;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::where('active', 1)
                    ->where('published_at', '<=', Carbon::now())
                    ->with(['categories' => function ($query) {
                        $query->select('slug', 'title');
                    }])
                    ->with(['user' => function ($query) {
                        $query->select('id', 'name');
                    }])
                    ->orderBy('published_at', 'desc')
                    ->paginate(5);
                    
        return view('home', compact('posts'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post, Request $request)
    {
        if(!$post->active || $post->published_at > Carbon::now()) {
            abort(404);
        }

        $prev = Post::where('active', 1)
                    ->where('published_at', '<=', Carbon::now())
                    ->where('published_at', '>', $post->published_at)
                    ->select('slug', 'title')
                    ->orderBy('published_at', 'asc')
                    ->limit(1)
                    ->first();

        $next = Post::where('active', 1)
                    ->where('published_at', '<=', Carbon::now())
                    ->where('published_at', '<', $post->published_at)
                    ->select('slug', 'title')
                    ->orderBy('published_at', 'desc')
                    ->limit(1)
                    ->first();

        $user = $request->user();

        PostView::create([
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'user_id' => $user?->id,
            'post_id' => $post->id,
        ]);

        return view('post.show', compact('post', 'next', 'prev'));
    }

    public function byCategory (Category $category) {
        $posts = Post::join('category_post', 'posts.id', '=', 'category_post.post_id')
                    ->where('category_post.category_id', $category->id)
                    ->where('active', 1)
                    ->where('published_at', '<=', Carbon::now())
                    ->select('posts.*')
                    ->with(['categories' => function ($query) {
                        $query->select('slug', 'title');
                    }])
                    ->with(['user' => function ($query) {
                        $query->select('id', 'name');
                    }])
                    ->orderBy('published_at', 'desc')
                    ->paginate(5);

        return view('category.index', compact('posts', 'category'));
    }
}
