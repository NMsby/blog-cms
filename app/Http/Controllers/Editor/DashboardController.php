<?php

namespace App\Http\Controllers\Editor;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Post;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'posts' => [
                'total' => Post::count(),
                'published' => Post::where('status', 'published')->count(),
                'draft' => Post::where('status', 'draft')->count(),
                'scheduled' => Post::where('status', 'scheduled')->count(),
            ],
            'comments' => [
                'total' => Comment::count(),
                'pending' => Comment::where('status', 'pending')->count(),
                'spam' => Comment::where('status', 'spam')->count(),
            ],
            'categories' => Category::count(),
        ];

        $recent_posts = Post::with(['user', 'categories'])
            ->latest()
            ->take(5)
            ->get();

        $pending_comments = Comment::with(['post', 'user'])
            ->where('status', 'pending')
            ->latest()
            ->take(5)
            ->get();

        $posts_by_category = Category::withCount('posts')
            ->orderByDesc('posts_count')
            ->take(5)
            ->get();

        return view('editor.dashboard', compact(
            'stats',
            'recent_posts',
            'pending_comments',
            'posts_by_category'
        ));
    }
}
