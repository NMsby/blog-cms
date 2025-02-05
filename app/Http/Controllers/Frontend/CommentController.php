<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Post;
use App\Notifications\CommentReplyNotification;
use App\Notifications\NewCommentNotification;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, Post $post)
    {
        $validated = $request->validate([
            'content' => 'required|string',
            'parent_id' => 'nullable|exists:comments,id',
        ]);

        $comment = Comment::create([
            'content' => $validated['content'],
            'post_id' => $post->id,
            'user_id' => auth()->id(),
            'parent_id' => $validated['parent_id'] ?? null,
            'status' => auth()->check() ? 'approved' : 'pending',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        // Send notifications
        if ($comment->parent_id) {
            // If this is a reply, notify the original commenter
            $comment->parent->user?->notify(new CommentReplyNotification($comment));
            return back()->with('success', 'Reply posted successfully.');
        } else {
            // If this is a new comment, notify the post author
            $post->user->notify(new NewCommentNotification($comment));
            return back()->with('success', 'Comment posted successfully.');
        }
    }
}
