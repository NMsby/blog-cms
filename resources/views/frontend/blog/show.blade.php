@extends('layouts.app')
@section('title', $post->title)

@section('content')
    <article class="max-w-4xl mx-auto px-4 py-8">
        <!-- Post Header -->
        <header class="mb-8">
            <h1 class="text-4xl font-bold mb-4">{{ $post->title }}</h1>
            <div class="flex items-center text-gray-600 mb-4">
                <span>{{ $post->created_at->format('M d, Y') }}</span>
                <span class="mx-2">&bull;</span>
                <span>{{ $post->user->name }}</span>
                <span class="mx-2">&bull;</span>
                <span>{{ $post->reading_time }} min read</span>
            </div>
            @if($post->categories->count() > 0)
                <div class="flex gap-2">
                    @foreach($post->categories as $category)
                        <a href="{{ route('blog.category', $category->slug) }}" class="text-sm bg-gray-100 px-3 py-1 rounded-full hover:bg-gray-200">
                            {{ $category->name }}
                        </a>
                    @endforeach
                </div>
            @endif
        </header>

        <!-- Featured Image -->
        @if($post->featured_image)
            <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}" class="w-full h-96 object-cover rounded-lg mb-8">
        @endif

        <!-- Content -->
        <div class="prose max-w-none mb-8">
            {!! $post->content !!}
        </div>

        <!-- Tags -->
        @if($post->tags->count() > 0)
            <div class="border-t pt-6 mb-8">
                <h3 class="text-lg font-semibold mb-2">Tags:</h3>
                <div class="flex flex-wrap gap-2">
                    @foreach($post->tags as $tag)
                        <a href="{{ route('blog.tag', $tag->slug) }}" class="bg-gray-100 px-3 py-1 rounded-full text-sm hover:bg-gray-200">
                            {{ $tag->name }}
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Comments Section -->
        @if($post->comments_enabled)
            <section class="border-t pt-8">
                <h3 class="text-2xl font-bold mb-6">Comments</h3>

                <!-- Comment Form -->
                <form action="{{ route('comments.store', $post) }}" method="POST" class="mb-8">
                    @csrf
                    <div class="mb-4">
                        <textarea name="content" rows="3" required
                                  class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                  placeholder="Leave a comment..."></textarea>
                    </div>
                    @guest
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <x-input-label for="guest_name" value="Name" />
                                <x-text-input id="guest_name" name="guest_name" type="text" required />
                            </div>
                            <div>
                                <x-input-label for="guest_email" value="Email" />
                                <x-text-input id="guest_email" name="guest_email" type="email" required />
                            </div>
                        </div>
                    @endguest
                    <x-primary-button>Post Comment</x-primary-button>
                </form>

                <!-- Comments List -->
                <div class="space-y-6">
                    @foreach($post->comments->whereNull('parent_id') as $comment)
                        @include('frontend.partials.comment', ['comment' => $comment])
                    @endforeach
                </div>
            </section>
        @endif

        <!-- Related Posts -->
        @if($related_posts->count() > 0)
            <section class="border-t pt-8">
                <h3 class="text-2xl font-bold mb-6">Related Posts</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($related_posts as $related_post)
                        @include('frontend.partials.post-card', ['post' => $related_post])
                    @endforeach
                </div>
            </section>
        @endif
    </article>
@endsection
