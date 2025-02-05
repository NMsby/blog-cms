{{-- resources/views/mail/notifications/comment-reply.blade.php --}}
<x-mail::message>
    # New Reply to Your Comment

    {{ $reply->user ? $reply->user->name : $reply->guest_name }} has replied to your comment on "{{ $reply->post->title }}".

    > {{ Str::limit($reply->content, 150) }}

    <x-mail::button :url="route('blog.show', ['post' => $reply->post, '#comment-' . $reply->id])">
        View Reply
    </x-mail::button>

    Thanks,<br>
    {{ config('app.name') }}
</x-mail::message>
