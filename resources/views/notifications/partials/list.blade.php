{{-- resources/views/notifications/partials/list.blade.php --}}
@forelse($notifications as $notification)
    <div class="notification-item p-4 hover:bg-gray-50" x-data="{ show: true }" x-show="show">
        <div class="flex items-start justify-between">
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-gray-900">
                    @if($notification->type === 'App\Notifications\NewCommentNotification')
                        {{ $notification->data['commenter'] }} commented on your post
                    @elseif($notification->type === 'App\Notifications\CommentReplyNotification')
                        {{ $notification->data['replier'] }} replied to your comment
                    @endif
                </p>
                <p class="text-sm text-gray-500 truncate">
                    {{ Str::limit($notification->data['content'] ?? '') }}
                </p>
                <p class="mt-1 text-xs text-gray-500">
                    {{ $notification->created_at->diffForHumans() }}
                </p>
            </div>
            <div class="ml-4 flex-shrink-0 flex">
                <button
                    @click="markAsRead('{{ $notification->id }}'); show = false"
                    class="text-xs text-blue-600 hover:text-blue-800">
                    Mark as read
                </button>
            </div>
        </div>
    </div>
@empty
    <div class="p-4 text-sm text-gray-500 text-center">
        No new notifications
    </div>
@endforelse
