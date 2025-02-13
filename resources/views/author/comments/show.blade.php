@extends('layouts.app')
@section('title', 'View Comment')
@section('header', 'View Comment')

@section('content')
    <div class="bg-white shadow-sm rounded-lg p-6">
        <div class="mb-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-2xl font-bold">Comment Details</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="font-semibold mb-2">Author Information</h3>
                    @if($comment->user)
                        <p>Name: {{ $comment->user->name }}</p>
                        <p>Email: {{ $comment->user->email }}</p>
                    @else
                        <p>Name: {{ $comment->guest_name }}</p>
                        <p>Email: {{ $comment->guest_email }}</p>
                    @endif
                    <p>IP Address: {{ $comment->ip_address }}</p>
                    <p>Date: {{ $comment->created_at->format('M d, Y H:i') }}</p>
                </div>

                <div>
                    <h3 class="font-semibold mb-2">Post Details</h3>
                    <p>Post: <a href="{{ route('blog.show', $comment->post) }}" class="text-blue-600 hover:text-blue-900">
                            {{ $comment->post->title }}
                        </a></p>
                </div>
            </div>

            <div class="mt-6">
                <h3 class="font-semibold mb-2">Comment Content</h3>
                <div class="bg-gray-50 p-4 rounded">
                    {!! nl2br(e($comment->content)) !!}
                </div>
            </div>

            <div x-data="{ showReplyForm: false }" class="mt-6">
                <button @click="showReplyForm = !showReplyForm" class="text-blue-600 hover:text-blue-900">
                    Reply to Comment
                </button>

                <form x-show="showReplyForm" @click.away="showReplyForm = false"
                      action="{{ route('author.comments.reply', $comment) }}" method="POST"
                      class="mt-4">
                    @csrf
                    <textarea name="content" rows="3" required
                              class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200"
                              placeholder="Write your reply..."></textarea>
                    <div class="mt-2">
                        <x-primary-button>Post Reply</x-primary-button>
                    </div>
                </form>
            </div>

            @if($comment->replies->count() > 0)
                <div class="mt-6">
                    <h3 class="font-semibold mb-2">Replies</h3>
                    <div class="space-y-4">
                        @foreach($comment->replies as $reply)
                            <div class="bg-gray-50 p-4 rounded">
                                <div class="flex justify-between mb-2">
                                    <span class="font-medium">{{ $reply->user ? $reply->user->name : $reply->guest_name }}</span>
                                    <span class="text-sm text-gray-500">{{ $reply->created_at->format('M d, Y H:i') }}</span>
                                </div>
                                <p>{!! nl2br(e($reply->content)) !!}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
