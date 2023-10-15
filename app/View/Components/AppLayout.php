<?php

namespace App\View\Components;

use Closure;
use App\Models\Category;
use Illuminate\Support\Carbon;
use Illuminate\View\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;

class AppLayout extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(public ?string $metaTitle = null, public ?string $metaDescription = null)
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $categories = Category::query()
            ->leftJoin('category_post', 'categories.id', '=', 'category_post.category_id')
            ->leftJoin('posts', 'category_post.post_id', '=', 'posts.id')
            ->where('posts.active', 1)
            ->where('posts.published_at', '<=', Carbon::now())
            ->select('categories.title', 'categories.slug', DB::raw('count(*) as total'))
            ->groupBy('categories.id')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->get();

        return view('layouts.app', compact('categories'));
    }
}
