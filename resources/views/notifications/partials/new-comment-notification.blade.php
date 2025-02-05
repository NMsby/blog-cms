{{-- resources/views/notifications/partials/new-comment-notification.blade.php --}}
<div class="flex justify-between items-start">
    <div class="flex-1">
        <p class="text-sm font-medium text-gray-900">
            {{ $notification->data['commenter'] }} commented on your post
        </p>
        <p class="mt-1 text-sm text-gray-600">
            "{{ Str::limit($notification->data['comment']) }}"
        </p>
        <div class="mt-2 text-xs text-gray-500 flex items-center space-x-2">
            <span>{{ $notification->created_at->diffForHumans() }}</span>
            <a href="{{ route('blog.show', ['post' => $notification->data['post_id'], '#comment-' . $notification->data['comment_id']]) }}"
               class="text-blue-600 hover:text-blue-800">
                View Comment
            </a>
        </div>
    </div>
    @unless($notification->read_at)
        <button onclick="markAsRead('{{ $notification->id }}')"
                class="text-xs text-blue-600 hover:text-blue-800">
            Mark as read
        </button>
    @endunless
</div>
