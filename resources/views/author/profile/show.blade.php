{{-- resources/views/author/profile/show.blade.php --}}
@extends('layouts.app')

@section('title', $user->name . ' - Author Profile')

@section('content')
    <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
        <div class="bg-white shadow rounded-lg">
            <!-- Profile Header -->
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <img class="h-24 w-24 rounded-full object-cover"
                             src="{{ $user->avatar_url }}"
                             alt="{{ $user->name }}">
                    </div>
                    <div class="ml-6">
                        <h1 class="text-2xl font-bold text-gray-900">{{ $user->name }}</h1>
                        <p class="text-sm text-gray-500">{{ '@' . $user->username }}</p>
                        <p class="mt-1 text-sm text-gray-500">Member since {{ $user->created_at->format('F Y') }}</p>
                    </div>
                </div>
            </div>

            <!-- Profile Content -->
            <div class="px-4 py-5 sm:p-6">
                <!-- Bio Section -->
                @if($user->bio)
                    <div class="mb-8">
                        <h2 class="text-lg font-semibold text-gray-900 mb-2">About</h2>
                        <p class="text-gray-700">{{ $user->bio }}</p>
                    </div>
                @endif

                <!-- Stats Section -->
                <div class="mb-8">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Statistics</h2>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="bg-gray-50 p-4 rounded-lg text-center">
                            <span class="block text-2xl font-bold text-gray-900">{{ $user->posts->count() }}</span>
                            <span class="text-sm text-gray-500">Posts</span>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg text-center">
                            <span class="block text-2xl font-bold text-gray-900">{{ $user->comments->count() }}</span>
                            <span class="text-sm text-gray-500">Comments</span>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg text-center">
                        <span class="block text-2xl font-bold text-gray-900">
                            {{ $user->posts->sum('view_count') }}
                        </span>
                            <span class="text-sm text-gray-500">Total Views</span>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg text-center">
                        <span class="block text-2xl font-bold text-gray-900">
                            {{ $user->posts->where('status', 'published')->count() }}
                        </span>
                            <span class="text-sm text-gray-500">Published Posts</span>
                        </div>
                    </div>
                </div>

                <!-- Recent Posts Section -->
                <div class="mb-8">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Recent Posts</h2>
                    <div class="space-y-4">
                        @forelse($user->posts()->published()->latest()->take(5)->get() as $post)
                            <div class="border-b border-gray-200 pb-4 last:border-0">
                                <a href="{{ route('blog.show', $post->slug) }}"
                                   class="text-lg font-medium text-blue-600 hover:text-blue-800">
                                    {{ $post->title }}
                                </a>
                                <p class="mt-1 text-sm text-gray-500">
                                    {{ $post->published_at->format('M d, Y') }} ·
                                    {{ $post->reading_time }} min read
                                </p>
                            </div>
                        @empty
                            <p class="text-gray-500">No published posts yet.</p>
                        @endforelse
                    </div>
                </div>

                <!-- Contact/Social Links -->
                @if($user->website || !empty(array_filter($user->social_links)))
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Connect</h2>
                        <div class="space-y-2">
                            @if($user->website)
                                <a href="{{ $user->website }}"
                                   target="_blank"
                                   class="flex items-center text-gray-600 hover:text-gray-900">
                                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                        <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $user->website }}
                                </a>
                            @endif

                            @foreach($user->social_links as $platform => $url)
                                @if($url)
                                    <a href="{{ $url }}"
                                       target="_blank"
                                       class="flex items-center text-gray-600 hover:text-gray-900">
                                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M6.29 18.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0020 3.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.073 4.073 0 01.8 7.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 010 16.407a11.616 11.616 0 006.29 1.84"/>
                                        </svg>
                                        {{ ucfirst($platform) }}
                                    </a>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
