@extends('layouts.admin')
@section('title', 'Dashboard')
@section('header', 'Dashboard')

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <!-- Posts Stats -->
        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="font-semibold text-lg mb-4">Posts</h3>
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
        </div>

        <!-- Comments Stats -->
        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="font-semibold text-lg mb-4">Comments</h3>
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

        <!-- Other Stats -->
        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="font-semibold text-lg mb-4">Overview</h3>
            <div class="space-y-2">
                <div class="flex justify-between">
                    <span>Categories</span>
                    <span class="font-bold">{{ $stats['categories'] }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Tags</span>
                    <span class="font-bold">{{ $stats['tags'] }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Users</span>
                    <span class="font-bold">{{ $stats['users'] }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Posts -->
        <div class="bg-white p-6 rounded-lg shadow">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-semibold text-lg">Recent Posts</h3>
                <a href="{{ route('admin.posts.index') }}" class="text-sm text-blue-600 hover:text-blue-800">View All</a>
            </div>
            <div class="divide-y">
                @foreach($recent_posts as $post)
                    <div class="py-3">
                        <a href="{{ route('admin.posts.edit', $post) }}" class="hover:text-blue-600">{{ $post->title }}</a>
                        <div class="text-sm text-gray-500">
                            by {{ $post->user->name }} • {{ $post->created_at->diffForHumans() }}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Recent Comments -->
        <div class="bg-white p-6 rounded-lg shadow">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-semibold text-lg">Recent Comments</h3>
                <a href="{{ route('admin.comments.index') }}" class="text-sm text-blue-600 hover:text-blue-800">View All</a>
            </div>
            <div class="divide-y">
                @foreach($recent_comments as $comment)
                    <div class="py-3">
                        <div class="text-sm">
                            <span class="font-medium">{{ $comment->user ? $comment->user->name : $comment->guest_name }}</span>
                            on
                            <a href="{{ route('admin.posts.edit', $comment->post) }}" class="text-blue-600 hover:text-blue-800">{{ $comment->post->title }}</a>
                        </div>
                        <p class="text-gray-600 text-sm">{{ Str::limit($comment->content) }}</p>
                        <div class="text-xs text-gray-500">{{ $comment->created_at->diffForHumans() }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
