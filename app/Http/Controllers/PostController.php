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
            ->where(function ($query) {
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
        $user = auth()->user();
        if ($user) {
            //select posts from the same category the user has upvoted and exclude the posts he actually upvoted
            $leftJoin = "(SELECT cp.category_id, cp.post_id FROM post_votes JOIN category_post cp
                        ON cp.post_id = post_votes.post_id WHERE post_votes.upvote = 1 AND post_votes.user_id = $user->id) as t";

            $recommendedPosts = Post::leftJoin('category_post as cp', 'posts.id', '=', 'cp.post_id')
                ->leftJoin(DB::raw($leftJoin), function ($join) {
                    $join->on('cp.category_id', '=', 't.category_id');
                })
                ->select('posts.*')
                ->where('cp.post_id', '<>', DB::raw('t.post_id'))
                ->where('active', 1)
                ->where('published_at', '<=', Carbon::now())
                ->groupBy('posts.id')
                ->limit(6)
                ->get();
        } else {
            $recommendedPosts = Post::leftJoin('post_views', 'posts.id', '=', 'post_views.post_id')
                ->select('posts.*', DB::raw('COUNT(post_views.id) as views_count'))
                ->where('active', 1)
                ->where('published_at', '<=', Carbon::now())
                ->groupBy('posts.id')
                ->orderBy('views_count', 'desc')
                ->limit(3)
                ->get();
        }

        //show categories with their latest posts
        $categories = Category::with(['posts'])
            ->whereHas('posts', function ($query) {
                $query->where('posts.active', 1)
                    ->where('posts.published_at', '<=', Carbon::now());
            })
            ->leftJoin('category_post', 'categories.id', '=', 'category_post.category_id')
            ->leftJoin('posts', 'category_post.post_id', '=', 'posts.id')
            ->select('categories.*')
            ->selectRaw('MAX(posts.published_at) as recent_date')
            ->orderBy('recent_date', 'desc')
            ->groupBy('categories.id')
            ->limit(3)
            ->get();

        return view('home', compact('latestPosts', 'popularPosts', 'recommendedPosts', 'categories'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post, Request $request)
    {
        if (!$post->active || $post->published_at > Carbon::now()) {
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

    public function byCategory(Category $category)
    {
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

    public function search(Request $request)
    {
        $posts = Post::where('active', 1)
            ->leftJoin('category_post', 'posts.id', '=', 'category_post.post_id')
            ->leftJoin('categories', 'category_post.category_id', '=', 'categories.id')
            ->where('published_at', '<=', Carbon::now())
            ->where(function ($query) use ($request) {
                $query->where('posts.title', 'like', '%' . $request->q . '%')
                    ->orWhere('posts.body', 'like', '%' . $request->q . '%')
                    ->orWhere('categories.title', 'like', '%' . $request->q . '%');
            })
            ->with(['categories' => function ($query) {
                $query->select('slug', 'title');
            }])
            ->select('posts.*')
            ->groupBy('posts.id')
            ->paginate(5);

        return view('post.search', compact('posts'));
    }
}
