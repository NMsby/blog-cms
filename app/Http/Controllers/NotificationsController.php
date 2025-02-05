<?php

namespace App\Http\Controllers;

use App\Notifications\CommentReplyNotification;
use App\Notifications\NewCommentNotification;
use App\Services\NotificationService;
use Exception;
use Illuminate\Http\Request;

class NotificationsController extends Controller
{
    public function index(Request $request)
    {
        $query = auth()->user()->notifications();

        // Filter notifications
        if ($request->has('type')) {
            $query->where('type', $request->type);
        }
        if ($request->has('read')) {
            $query->whereNotNull('read_at', $request->read);
        } elseif ($request->has('unread')) {
            $query->whereNull('read_at');
        }

        $notifications = $query->latest()->paginate(20);

        // Group notifications by date and type
        $groupedNotifications = $notifications->groupBy(function ($notification) {
            return [
                'date' => $notification->created_at->format('Y-m-d'),
                'type' => $notification->type
            ];
        });

        // Get notification stats
        $stats = [
            'total' => auth()->user()->notifications()->count(),
            'unread' => auth()->user()->unreadNotifications->count(),
            'types' => auth()->user()->notifications()
                ->select('type')
                ->distinct()
                ->pluck('type')
        ];

        return view('notifications.index', compact('groupedNotifications', 'notifications', 'stats'));
    }

    public function markAsRead(Request $request)
    {
        try {
            $notification = auth()->user()
                ->notifications()
                ->findOrFail($request->id);

            $notification->markAsRead();

            NotificationService::toast('Notification marked as read', 'success');

            return response()->json(['success' => true]);
        } catch (Exception) {
            return response()->json(['success' => false, 'message' => 'Error marking notification as read'], 500);
        }
    }

    public function markAllAsRead()
    {
        try {
            auth()->user()->unreadNotifications->markAsRead();
            NotificationService::toast('All notifications marked as read', 'success');

            return back();
        } catch (Exception) {
            return back()->with('error', 'Error marking all notifications as read');
        }
    }

    public function destroy(Request $request)
    {
        try {
            auth()->user()
                ->notifications()
                ->findOrFail($request->id)
                ->delete();

            NotificationService::toast('Notification deleted', 'success');
            return response()->json(['success' => true]);
        } catch (Exception) {
            return response()->json(['success' => false, 'message' => 'Error deleting notification'], 500);
        }
    }

    public function clearAll()
    {
        try {
            auth()->user()->notifications()->delete();
            NotificationService::toast('All notifications cleared', 'success');

            return back();
        } catch (Exception) {
            return back()->with('error', 'Error clearing all notifications');
        }
    }

    public function getUnreadCount()
    {
        $count = auth()->user()
            ->unreadNotifications
            ->count();

        return response()->json(['count' => $count]);
    }

    public function show($id)
    {
        $notification = auth()->user()
            ->notifications()
            ->findorFail($id);

        // Mark the notification as read
        if (!$notification->read_at) {
            $notification->markAsRead();
        }

        // Redirect based on notification type
        return match ($notification->type) {
            NewCommentNotification::class => redirect()->route('blog.show', [
                'post' => $notification->data['post_id'],
                '#comment-' . $notification->data['comment_id']
            ]),
            CommentReplyNotification::class => redirect()->route('blog.show', [
                'post' => $notification->data['post_id'],
                '#comment-' . $notification->data['reply_id']
            ]),
            default => redirect()->route('notifications.index'),
        };
    }

    public function preferences()
    {
        $preferences = auth()->user()->notification_preferences ?? [
            'email_notifications' => true,
            'web_notifications' => true,
            'comment_notifications' => true,
            'reply_notifications' => true,
        ];

        return view('notifications.preferences', compact('preferences'));
    }

    public function updatePreferences(Request $request)
    {
        $validated = $request->validate([
            'email_notifications' => 'boolean',
            'web_notifications' => 'boolean',
            'comment_notifications' => 'boolean',
            'reply_notifications' => 'boolean',
        ]);

        try {
            auth()->user()->update([
                'notification_preferences' => $validated
            ]);

            NotificationService::toast('Notification preferences updated', 'success');
            return back();
        } catch (Exception) {
            return back()->with('error', 'Error updating notification preferences');
        }
    }

    public function getPartial()
    {
        $notifications = auth()->user()
            ->unreadNotifications()
            ->latest()
            ->take(5)
            ->get();

        return view('notifications.partials.list', compact('notifications'));
    }
}
