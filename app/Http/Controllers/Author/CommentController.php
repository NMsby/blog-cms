<?php

namespace App\Http\Controllers\Author;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Post;
use App\Notifications\CommentReplyNotification;
use App\Notifications\NewCommentNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function index()
    {
        $comments = Comment::whereHas('post', function ($query) {
            $query->where('user_id', Auth::id());
        })->latest()->paginate(15);

        return view('author.comments.index', compact('comments'));
    }

    public function show(Comment $comment)
    {
        if ($comment->post->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $comment->load(['post', 'user', 'replies']);
        return view('author.comments.show', compact('comment'));
    }

    public function reply(Request $request, Comment $comment, Post $post)
    {
        if ($comment->post->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        Comment::create([
            'post_id' => $comment->post_id,
            'user_id' => Auth::id(),
            'parent_id' => $comment->id,
            'content' => $validated['content'],
            'status' => 'approved',
        ]);

        // Send notifications
        if ($comment->status === 'approved') {
            if ($comment->parent_id) {
                // If this is a reply, notify the original commenter
                $comment->parent->user?->notify(new CommentReplyNotification($comment));
            } else {
                // If this is a new comment, notify the post author
                $post->user->notify(new NewCommentNotification($comment));
            }
        }

        return redirect()->back()
            ->with('success', 'Your reply has been added successfully.');
    }
}
