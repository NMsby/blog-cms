@extends('layouts.app')
@section('title', 'Comments')
@section('header', 'Comments on My Posts')

@section('content')
    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Author</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Comment</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Post</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
            </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
            @foreach($comments as $comment)
                <tr>
                    <td class="px-6 py-4">
                        {{ $comment->user ? $comment->user->name : $comment->guest_name }}
                        @if(!$comment->user)
                            <div class="text-sm text-gray-500">{{ $comment->guest_email }}</div>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm">{{ Str::limit($comment->content, 100) }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <a href="{{ route('blog.show', $comment->post->slug) }}" class="text-blue-600 hover:text-blue-900">
                            {{ Str::limit($comment->post->title, 30) }}
                        </a>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        {{ $comment->created_at->format('M d, Y H:i') }}
                    </td>
                    <td class="px-6 py-4 text-sm font-medium">
                        <a href="{{ route('author.comments.show', $comment) }}" class="text-blue-600 hover:text-blue-900">View</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div class="px-6 py-4">
            {{ $comments->links() }}
        </div>
    </div>
@endsection
