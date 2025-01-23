<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'posts_count' => Post::count(),
            'published_posts' => Post::published()->count(),
            'comments_count' => Comment::count(),
            'pending_comments' => Comment::where('status', 'pending')->count(),
            'users_count' => User::count(),
            'recent_posts' => Post::with('user')
                ->latest()
                ->take(5)
                ->get(),
            'recent_comments' => Comment::with(['post', 'user'])
                ->latest()
                ->take(5)
                ->get(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
