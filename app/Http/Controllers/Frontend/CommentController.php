<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use App\Notifications\CommentReplyNotification;
use App\Notifications\NewCommentNotification;
use App\Services\SpamDetector;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    protected SpamDetector $spamDetector;

    public function __construct(SpamDetector $spamDetector)
    {
        $this->middleware('auth');
        $this->spamDetector = $spamDetector;
    }

    public function store(Request $request, Post $post)
    {
        $validated = $request->validate([
            'content' => 'required|string',
            'parent_id' => 'nullable|exists:comments,id',
        ]);

        // Spam Check
        if ($this->spamDetector->isSpam($validated['content'], auth()->user())) {
            return back()->with('error', 'Your comment has been flagged as potential spam. Please review and try again.');
        }

        // Determine initial status based on user trust level
        $status = $this->determineInitialStatus(auth()->user());


        $comment = Comment::create([
            'content' => $validated['content'],
            'post_id' => $post->id,
            'user_id' => auth()->id(),
            'parent_id' => $validated['parent_id'] ?? null,
            'status' => auth()->check() ? 'approved' : 'pending',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        // Send notifications only if comment is auto-approved
        if ($status === Comment::STATUS_APPROVED) {
            if ($comment->parent_id) {
                // If this is a reply, notify the original commenter
                $comment->parent->user?->notify(new CommentReplyNotification($comment));
                $message = 'Reply posted successfully.';
            } else {
                // If this is a new comment, notify the post-author
                $post->user->notify(new NewCommentNotification($comment));
                $message = 'Comment posted successfully.';
            }
        } else {
            $message = 'Your comment has been submitted for moderation.';
        }

        return back()->with('success', $message);
    }

    protected function determineInitialStatus(User $user): string
    {
        // Auto-approve comments from trusted users
        if ($user->isAdmin() || $user->isEditor() || $this->isUserTrusted($user)) {
            return Comment::STATUS_APPROVED;
        }

        return Comment::STATUS_PENDING;
    }

    protected function isUserTrusted(User $user): bool
    {
        // Count the number of approved comments from this user
        $approvedComments = Comment::where('user_id', $user->id)
            ->where('status', Comment::STATUS_APPROVED)
            ->count();

        // Trust users with more than 5 approved comments
        return $approvedComments >= 5;
    }

    public function edit(Comment $comment)
    {
        if (!$comment->canEdit(auth()->user())) {
            abort(403);
        }

        return view('frontend.comments.edit', compact('comment'));
    }

    public function update(Request $request, Comment $comment)
    {
        if (!$comment->canEdit(auth()->user())) {
            abort(403);
        }

        $validated = $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $comment->update($validated);

        return redirect()->back()
            ->with('success', 'Comment updated successfully.');
    }

    public function destroy(Comment $comment)
    {
        if (!$comment->canEdit(auth()->user())) {
            abort(403);
        }

        $comment->delete();

        return redirect()->back()
            ->with('success', 'Comment deleted successfully.');
    }
}
