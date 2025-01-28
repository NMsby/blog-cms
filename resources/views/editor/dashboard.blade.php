{{-- resources/views/editor/dashboard.blade.php --}}
@extends('layouts.app')
@section('title', 'Editor Dashboard')

@section('content')
    <!-- Quick Actions -->
    <div class="mb-6">
        <div class="flex flex-wrap gap-4">
            <a href="{{ route('admin.posts.create') }}"
               class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                New Post
            </a>
            <a href="{{ route('admin.comments.pending') }}"
               class="inline-flex items-center px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"></path>
                </svg>
                Moderate Comments
            </a>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <!-- Posts Stats -->
        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="font-semibold text-lg mb-4">Content Overview</h3>
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
                <div class="flex justify-between text-blue-600">
                    <span>Scheduled</span>
                    <span class="font-bold">{{ $stats['posts']['scheduled'] }}</span>
                </div>
            </div>
        </div>

        <!-- Comments Stats -->
        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="font-semibold text-lg mb-4">Comments Status</h3>
            <div class="space-y-2">
                <div class="flex justify-between">
                    <span>Total Comments</span>
                    <span class="font-bold">{{ $stats['comments']['total'] }}</span>
                </div>
                <div class="flex justify-between text-yellow-600">
                    <span>Pending</span>
                    <span class="font-bold">{{ $stats['comments']['pending'] }}</span>
                </div>
                <div class="flex justify-between text-red-600">
                    <span>Spam</span>
                    <span class="font-bold">{{ $stats['comments']['spam'] }}</span>
                </div>
            </div>
        </div>

        <!-- Categories Stats -->
        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="font-semibold text-lg mb-4">Categories Overview</h3>
            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <span>Total Categories</span>
                    <span class="font-bold">{{ $stats['categories'] }}</span>
                </div>
                <div class="space-y-2">
                    @foreach($posts_by_category as $category)
                        <div class="flex justify-between text-sm">
                            <span>{{ $category->name }}</span>
                            <span>{{ $category->posts_count }} posts</span>
                        </div>
                    @endforeach
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
                        <div class="flex justify-between items-start">
                            <div>
                                <a href="{{ route('admin.posts.edit', $post) }}" class="hover:text-blue-600 font-medium">
                                    {{ $post->title }}
                                </a>
                                <div class="text-sm text-gray-500">
                                    by {{ $post->user->name }} • {{ $post->created_at->diffForHumans() }}
                                </div>
                            </div>
                            <span class="px-2 py-1 text-xs font-semibold rounded-full
                                {{ $post->status === 'published' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ ucfirst($post->status) }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Pending Comments -->
        <div class="bg-white p-6 rounded-lg shadow">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-semibold text-lg">Pending Comments</h3>
                <a href="{{ route('admin.comments.index', ['status' => 'pending']) }}"
                   class="text-sm text-blue-600 hover:text-blue-800">View All</a>
            </div>
            <div class="divide-y">
                @foreach($pending_comments as $comment)
                    <div class="py-3">
                        <div class="flex items-start justify-between">
                            <div>
                                <div class="text-sm">
                                    <span class="font-medium">{{ $comment->user ? $comment->user->name : $comment->guest_name }}</span>
                                    on
                                    <a href="{{ route('admin.posts.edit', $comment->post) }}"
                                       class="text-blue-600 hover:text-blue-800">
                                        {{ $comment->post->title }}
                                    </a>
                                </div>
                                <p class="text-gray-600 text-sm mt-1">{{ Str::limit($comment->content) }}</p>
                                <div class="text-xs text-gray-500 mt-1">{{ $comment->created_at->diffForHumans() }}</div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
