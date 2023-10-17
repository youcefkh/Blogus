<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Post;
use App\Models\Category;
use App\Models\PostView;
use Illuminate\Http\Request;
use Illuminate\Cache\RateLimiting\Limit;
use App\Traits\PostTrait;
use Illuminate\Support\Facades\DB;

use function Laravel\Prompts\select;

class PostController extends Controller
{
    use PostTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //Latest posts
        $latestPosts = Post::where('active', 1)
                    ->where('published_at', '<=', Carbon::now())
                    ->orderBy('published_at', 'desc')
                    ->limit(8)
                    ->get();

        //show most popular 3 posts based on upvotes
        $popularPosts = Post::leftJoin('post_votes', 'posts.id', '=', 'post_votes.post_id')
                            ->select('posts.*', DB::raw('COUNT(post_votes.upvote) as likes'))
                            ->where(function($query){
                                $query->whereNull('post_votes.upvote')
                                    ->orWhere('post_votes.upvote', 1);
                            })
                            ->where('active', 1)
                            ->where('published_at', '<=', Carbon::now())
                            ->groupBy('posts.id')
                            ->orderBy('likes', 'desc')
                            ->limit(3)
                            ->get();

        //if authorized - show recommended posts based on upvotes
        //if not - popular posts based on views

        //show categories with their latest posts
        
        return view('home', compact('latestPosts', 'popularPosts'));
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

        /** count views */
        $this->countView($request, $post);

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
