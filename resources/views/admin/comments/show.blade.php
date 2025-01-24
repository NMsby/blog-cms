@extends('layouts.admin')
@section('title', 'View Comment')
@section('header', 'View Comment')

@section('content')
    <div class="bg-white shadow-sm rounded-lg p-6">
        <div class="mb-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-2xl font-bold">Comment Details</h2>
                <div class="flex gap-2">
                    <form action="{{ route('admin.comments.update', $comment) }}" method="POST" class="inline">
                        @csrf
                        @method('PUT')
                        @if($comment->status !== 'approved')
                            <button type="submit" name="status" value="approved" class="bg-green-500 text-white px-3 py-1 rounded text-sm hover:bg-green-600">
                                Approve
                            </button>
                        @endif
                        @if($comment->status !== 'spam')
                            <button type="submit" name="status" value="spam" class="bg-yellow-500 text-white px-3 py-1 rounded text-sm hover:bg-yellow-600">
                                Mark as Spam
                            </button>
                        @endif
                    </form>
                    <form action="{{ route('admin.comments.destroy', $comment) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded text-sm hover:bg-red-600" onclick="return confirm('Are you sure?')">
                            Delete
                        </button>
                    </form>
                </div>
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
                    <h3 class="font-semibold mb-2">Comment Details</h3>
                    <p>Status:
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                            {{ $comment->status === 'approved' ? 'bg-green-100 text-green-800' :
                               ($comment->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                            {{ ucfirst($comment->status) }}
                        </span>
                    </p>
                    <p>Post: <a href="{{ route('admin.posts.show', $comment->post) }}" class="text-blue-600 hover:text-blue-900">
                            {{ $comment->post->title }}
                        </a></p>
                    @if($comment->parent)
                        <p>Reply to: <a href="{{ route('admin.comments.show', $comment->parent) }}" class="text-blue-600 hover:text-blue-900">
                                {{ Str::limit($comment->parent->content, 50) }}
                            </a></p>
                    @endif
                </div>
            </div>

            <div class="mt-6">
                <h3 class="font-semibold mb-2">Comment Content</h3>
                <div class="bg-gray-50 p-4 rounded">
                    {!! nl2br(e($comment->content)) !!}
                </div>
            </div>

            @if($comment->replies->count() > 0)
                <div class="mt-6">
                    <h3 class="font-semibold mb-2">Replies ({{ $comment->replies->count() }})</h3>
                    <div class="space-y-4">
                        @foreach($comment->replies as $reply)
                            <div class="bg-gray-50 p-4 rounded">
                                <div class="flex justify-between mb-2">
                                    <span class="font-medium">{{ $reply->user ? $reply->user->name : $reply->guest_name }}</span>
                                    <span class="text-sm text-gray-500">{{ $reply->created_at->format('M d, Y H:i') }}</span>
                                </div>
                                <p>{!! nl2br(e($reply->content)) !!}</p>
                                <div class="mt-2">
                                    <a href="{{ route('admin.comments.show', $reply) }}" class="text-sm text-blue-600 hover:text-blue-900">View Reply</a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
