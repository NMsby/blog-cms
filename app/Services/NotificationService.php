<?php

namespace App\Services;

use App\Events\NewNotification;
use Illuminate\Broadcasting\Broadcasters\Broadcaster;
use Illuminate\Support\Facades\Broadcast;

class NotificationService
{
    public static function toast(string $message, string $type = 'info'): void
    {
        if (request()->ajax()) {
            response()->json([
                'toast' => [
                    'message' => $message,
                    'type' => $type
                ]
            ]);
            return;
        }

        session()->flash('toast', [
            'message' => $message,
            'type' => $type
        ]);
    }

    public static function broadcast($user, string $message, string $type = 'info'): bool|Broadcaster
    {
        if ($user->notification_preferences['web_notifications'] ?? true) {
            return Broadcast::channel('notifications.' . $user->id, function() use ($message, $type) {
                return event(new NewNotification([
                    'message' => $message,
                    'type' => $type
                ]));
            });
        }

        return false;
    }
}
