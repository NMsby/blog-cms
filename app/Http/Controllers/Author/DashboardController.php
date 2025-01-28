<?php

namespace App\Http\Controllers\Author;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'posts' => [
                'total' => Post::where('user_id', Auth::id())->count(),
                'published' => Post::where('user_id', Auth::id())
                    ->where('status', 'published')
                    ->count(),
                'draft' => Post::where('user_id', Auth::id())
                    ->where('status', 'draft')
                    ->count(),
            ],
            'comments' => [
                'total' => Comment::whereHas('post', function($query) {
                    $query->where('user_id', Auth::id());
                })->count(),
                'pending' => Comment::whereHas('post', function($query) {
                    $query->where('user_id', Auth::id());
                })->where('status', 'pending')->count(),
            ],
        ];

        $recent_posts = Post::with('user')
            ->where('user_id', Auth::id())
            ->latest()
            ->take(5)
            ->get();

        $recent_comments = Comment::with(['post', 'user'])
            ->whereHas('post', function($query) {
                $query->where('user_id', Auth::id());
            })
            ->latest()
            ->take(5)
            ->get();

        return view('author.dashboard', compact('stats', 'recent_posts', 'recent_comments'));
    }
}
