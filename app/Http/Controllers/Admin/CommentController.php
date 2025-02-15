<?php

namespace App\Http\Controllers\Admin;

use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends AdminController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Comment::query();
        $status = $request->input('status');

        if ($status) {
            $query->where('status', $status);
        }

        $comments = $query->with(['user', 'post'])
            ->latest()
            ->paginate(15);

        $counts = [
            'total' => Comment::count(),
            'pending' => Comment::where('status', 'pending')->count(),
            'approved' => Comment::where('status', 'approved')->count(),
            'spam' => Comment::where('status', 'spam')->count(),
        ];

        return view('admin.comments.index', compact('comments', 'counts'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Comment $comment)
    {
        $comment->load(['post', 'user', 'parent', 'replies']);

        return view('admin.comments.show', compact('comment'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Comment $comment)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,approved,spam'
        ]);

        $comment->update($validated);

        return redirect()->route('admin.comments.index')
            ->with('success', 'Comment status updated successfully.');
    }

    /**
     * Approve the specified comment.
     */
    public function approve(Comment $comment)
    {
        $comment->update(['status' => Comment::STATUS_APPROVED]);

        return back()->with('success', 'Comment approved successfully.');
    }

    /*
     * Mark the specified comment as spam.
     */
    public function markAsSpam(Comment $comment)
    {
        $comment->update(['status' => Comment::STATUS_SPAM]);
        return back()->with('success', 'Comment marked as spam successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment)
    {
        $comment->delete();

        return redirect()->route('admin.comments.index')
            ->with('success', 'Comment deleted successfully.');
    }

    /**
     * Batch actions for comments.
     */
    public function batch(Request $request)
    {
        $validated = $request->validate([
            'comment_ids' => 'required|array',
            'comment_ids.*' => 'exists:comments,id',
            'action' => 'required|in:approve,mark_as_spam,delete'
        ]);

        $comments = Comment::whereIn('id', $validated['comment_ids']);
        $message = '';

        switch($request->action) {
            case 'approve':
                $comments->update(['status' => 'approved']);
                $message = 'Comments approved successfully.';
                break;
            case 'mark_as_spam':
                $comments->update(['status' => 'spam']);
                $message = 'Comments marked as spam successfully.';
                break;
            case 'delete':
                $comments->delete();
                $message = 'Comments deleted successfully.';
                break;
        }

        return back()->with('success', $message);
    }

    /**
     * Display pending comments.
     */
    public function pending()
    {
        $comments = Comment::with(['post', 'user'])
            ->where('status', 'pending')
            ->latest()
            ->paginate(15);

        return view('admin.comments.pending', compact('comments'));
    }


}
