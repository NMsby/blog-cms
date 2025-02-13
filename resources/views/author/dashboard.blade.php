{{-- resources/views/author/dashboard.blade.php --}}
@extends('layouts.app')
@section('title', 'Author Dashboard')

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <!-- Posts Stats -->
        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="font-semibold text-lg mb-4">Your Posts</h3>
            <div class="space-y-2">
                <div class="flex justify-between">
                    <span>Total Posts</span>
                    <span class="font-bold">{{ $stats['posts']['total'] }}</span>
                </div>
                <div class="flex justify-between text-green-600">
                    <span>Published</span>
                    <span class="font-bold">{{ $stats['posts']['published'] }}</span>
                </div>
                <div class="flex justify-between text-yellow-600">
                    <span>Drafts</span>
                    <span class="font-bold">{{ $stats['posts']['draft'] }}</span>
                </div>
            </div>
            <div class="mt-4">
                <a href="{{ route('authorposts.create') }}" class="text-blue-600 hover:text-blue-800 text-sm">
                    + Create New Post
                </a>
            </div>
        </div>

        <!-- Comments Stats -->
        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="font-semibold text-lg mb-4">Comments on Your Posts</h3>
            <div class="space-y-2">
                <div class="flex justify-between">
                    <span>Total Comments</span>
                    <span class="font-bold">{{ $stats['comments']['total'] }}</span>
                </div>
                <div class="flex justify-between text-yellow-600">
                    <span>Pending</span>
                    <span class="font-bold">{{ $stats['comments']['pending'] }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Posts -->
        <div class="bg-white p-6 rounded-lg shadow">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-semibold text-lg">Your Recent Posts</h3>
                <a href="{{ route('authorposts.index') }}" class="text-sm text-blue-600 hover:text-blue-800">View All</a>
            </div>
            <div class="divide-y">
                @forelse($recent_posts as $post)
                    <div class="py-3">
                        <div class="flex justify-between items-start">
                            <div>
                                <a href="{{ route('authorposts.edit', $post) }}" class="hover:text-blue-600 font-medium">
                                    {{ $post->title }}
                                </a>
                                <div class="text-sm text-gray-500">
                                    {{ $post->created_at->diffForHumans() }}
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        {{ $post->status === 'published' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ ucfirst($post->status) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 text-center py-4">No posts yet</p>
                @endforelse
            </div>
        </div>

        <!-- Recent Comments -->
        <div class="bg-white p-6 rounded-lg shadow">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-semibold text-lg">Recent Comments</h3>
            </div>
            <div class="divide-y">
                @forelse($recent_comments as $comment)
                    <div class="py-3">
                        <div class="text-sm">
                            <span class="font-medium">{{ $comment->user ? $comment->user->name : $comment->guest_name }}</span>
                            on
                            <a href="{{ route('admin.posts.edit', $comment->post) }}" class="text-blue-600 hover:text-blue-800">
                                {{ $comment->post->title }}
                            </a>
                        </div>
                        <p class="text-gray-600 text-sm mt-1">{{ Str::limit($comment->content) }}</p>
                        <div class="text-xs text-gray-500 mt-1">{{ $comment->created_at->diffForHumans() }}</div>
                    </div>
                @empty
                    <p class="text-gray-500 text-center py-4">No comments yet</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection
