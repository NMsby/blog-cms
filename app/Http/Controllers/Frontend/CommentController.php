<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, Post $post)
    {
        $validated = $request->validate([
            'content' => 'required|string',
            'parent_id' => 'nullable|exists:comments,id',
            'guest_name' => 'required_without:user_id|string|max:255',
            'guest_email' => 'required_without:user_id|email|max:255',
        ]);

        $comment = new Comment($validated);
        $comment->post_id = $post->id;
        $comment->user_id = auth()->id();
        $comment->status = auth()->check() ? 'approved' : 'pending';
        $comment->ip_address = $request->ip();
        $comment->user_agent = $request->userAgent();
        $comment->save();

        return back()->with('success', 'Comment submitted successfully.');
    }
}
