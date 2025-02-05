{{-- resources/views/mail/notifications/new-comment.blade.php --}}
<x-mail::message>
    # New Comment on Your Post

    {{ $comment->user ? $comment->user->name : $comment->guest_name }} has commented on your post "{{ $comment->post->title }}".

    > {{ Str::limit($comment->content, 150) }}

    <x-mail::button :url="route('blog.show', ['post' => $comment->post, '#comment-' . $comment->id])">
        View Comment
    </x-mail::button>

    Thanks,<br>
    {{ config('app.name') }}
</x-mail::message>
