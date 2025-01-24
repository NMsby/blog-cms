@extends('layouts.app')
@section('title', 'Home')

@section('content')
    <!-- Featured Posts -->
    @if($featured_posts->count() > 0)
        <section class="bg-white py-12">
            <div class="max-w-7xl mx-auto px-4">
                <h2 class="text-3xl font-bold mb-8">Featured Posts</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($featured_posts as $post)
                        <article class="bg-white rounded-lg shadow-lg overflow-hidden">
                            @if($post->featured_image)
                                <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}" class="w-full h-48 object-cover">
                            @endif
                            <div class="p-6">
                                <h3 class="text-xl font-bold mb-2">
                                    <a href="{{ route('blog.show', $post->slug) }}" class="hover:text-blue-600">{{ $post->title }}</a>
                                </h3>
                                <p class="text-gray-600 mb-4">{{ Str::limit($post->excerpt) }}</p>
                                <div class="flex items-center text-sm text-gray-500">
                                    <span>{{ $post->created_at->format('M d, Y') }}</span>
                                    <span class="mx-2">&bull;</span>
                                    <span>{{ $post->user->name }}</span>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- Recent Posts -->
    <section class="py-12">
        <div class="max-w-7xl mx-auto px-4">
            <h2 class="text-3xl font-bold mb-8">Recent Posts</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($recent_posts as $post)
                    @include('frontend.partials.post-card', ['post' => $post])
                @endforeach
            </div>
        </div>
    </section>

    <!-- Categories -->
    <section class="bg-gray-50 py-12">
        <div class="max-w-7xl mx-auto px-4">
            <h2 class="text-3xl font-bold mb-8">Categories</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach($categories as $category)
                    <a href="{{ route('blog.category', $category->slug) }}" class="bg-white p-4 rounded-lg shadow hover:shadow-md transition">
                        <h3 class="font-semibold">{{ $category->name }}</h3>
                        <p class="text-sm text-gray-500">{{ $category->posts_count }} posts</p>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
@endsection
