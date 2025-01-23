<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;

class HomeController extends Controller
{
    public function index()
    {
        $featured_posts = Post::with(['user', 'categories'])
            ->published()
            ->featured()
            ->latest('published_at')
            ->take(5)
            ->get();

        $recent_posts = Post::with(['user', 'categories'])
            ->published()
            ->latest('published_at')
            ->take(6)
            ->get();

        $categories = Category::withCount('posts')
            ->whereHas('posts', function($query) {
                $query->published();
            })
            ->visible()
            ->take(10)
            ->get();

        return view('frontend.home', compact(
            'featured_posts',
            'recent_posts',
            'categories'
        ));
    }
}
