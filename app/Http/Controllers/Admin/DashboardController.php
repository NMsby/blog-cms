<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;

class DashboardController extends AdminController
{
    public function index()
    {
        $stats = [
            'posts' => [
                'total' => Post::count(),
                'published' => Post::where('status', 'published')->count(),
                'draft' => Post::where('status', 'draft')->count(),
            ],
            'comments' => [
                'total' => Comment::count(),
                'pending' => Comment::where('status', 'pending')->count(),
            ],
            'categories' => Category::count(),
            'tags' => Tag::count(),
            'users' => User::count(),
        ];

        $recent_posts = Post::with('user')
        ->latest()
        ->take(5)
        ->get();

        $recent_comments = Comment::with(['post', 'user'])
        ->latest()
        ->take(5)
        ->get();

        return view('admin.dashboard', compact('stats', 'recent_posts', 'recent_comments'));
    }
}
