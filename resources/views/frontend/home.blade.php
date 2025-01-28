{{-- resources/views/frontend/home.blade.php --}}
@extends('layouts.app')
@section('title', 'Home')

@section('content')
    @if($featured_posts->count() > 0)
        <section class="bg-gradient-to-b from-gray-900 to-gray-800 text-white py-16">
            <div class="max-w-7xl mx-auto px-4">
                <h2 class="text-3xl font-bold mb-8">Featured Stories</h2>
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($featured_posts as $post)
                        <article class="bg-gray-800 rounded-lg overflow-hidden hover:transform hover:scale-105 transition-transform">
                            @if($post->featured_image)
                                <div class="aspect-video">
                                    <img src="{{ asset('storage/' . $post->featured_image) }}"
                                         alt="{{ $post->title }}"
                                         class="w-full h-full object-cover">
                                </div>
                            @endif
                            <div class="p-6">
                                <h3 class="text-xl font-bold mb-2">
                                    <a href="{{ route('blog.show', $post->slug) }}" class="hover:text-blue-400">
                                        {{ $post->title }}
                                    </a>
                                </h3>
                                <p class="text-gray-400 mb-4">{{ Str::limit($post->excerpt, 120) }}</p>
                                <div class="flex items-center text-sm text-gray-500">
                                    <span>{{ $post->user->name }}</span>
                                    <span class="mx-2">&middot;</span>
                                    <span>{{ $post->created_at->format('M d, Y') }}</span>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <section class="py-16">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-3xl font-bold">Latest Posts</h2>
                <a href="{{ route('blog.index') }}" class="text-blue-600 hover:text-blue-800">View All</a>
            </div>
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($recent_posts as $post)
                    @include('frontend.partials.post-card', ['post' => $post])
                @endforeach
            </div>
        </div>
    </section>

    <section class="bg-gray-50 py-16">
        <div class="max-w-7xl mx-auto px-4">
            <h2 class="text-3xl font-bold mb-8">Browse Categories</h2>
            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
                @foreach($categories as $category)
                    <a href="{{ route('blog.category', $category->slug) }}"
                       class="bg-white p-6 rounded-lg shadow hover:shadow-md transition group">
                        <h3 class="font-semibold mb-1 group-hover:text-blue-600">{{ $category->name }}</h3>
                        <div class="flex justify-between text-sm text-gray-500">
                            <span>{{ $category->posts_count }} {{ Str::plural('post', $category->posts_count) }}</span>
                            <span class="group-hover:translate-x-2 transition-transform">&rarr;</span>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
@endsection
