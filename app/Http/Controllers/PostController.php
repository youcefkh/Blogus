<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;

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
                        $query->select('category_id', 'title');
                    }])
                    ->with(['user' => function ($query) {
                        $query->select('id', 'name');
                    }])
                    ->orderBy('published_at', 'desc')
                    ->paginate(5);
                    
        return view('home', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
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

        return view('post.show', compact('post', 'next', 'prev'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        //
    }
}
