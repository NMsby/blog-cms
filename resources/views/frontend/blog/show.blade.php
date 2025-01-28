@extends('layouts.app')
@section('title', $post->title)

@section('content')
    <article class="max-w-4xl mx-auto px-4 py-8">
        <header class="mb-8">
            @if($post->featured_image)
                <div class="rounded-xl overflow-hidden mb-8 aspect-video">
                    <img src="{{ asset('storage/' . $post->featured_image) }}"
                         alt="{{ $post->title }}"
                         class="w-full h-full object-cover">
                </div>
            @endif

            <h1 class="text-4xl font-bold mb-4">{{ $post->title }}</h1>

            <div class="flex flex-wrap items-center gap-4 text-gray-600 mb-6">
                <div class="flex items-center">
                    @if($post->user->avatar)
                        <img src="{{ asset('storage/' . $post->user->avatar) }}"
                             class="w-10 h-10 rounded-full mr-3" alt="">
                    @endif
                    <span>{{ $post->user->name }}</span>
                </div>
                <span>&middot;</span>
                <time datetime="{{ $post->published_at->format('Y-m-d') }}">
                    {{ $post->published_at->format('M d, Y') }}
                </time>
                <span>&middot;</span>
                <span>{{ $post->reading_time }} min read</span>
            </div>

            @if($post->categories->count())
                <div class="flex flex-wrap gap-2">
                    @foreach($post->categories as $category)
                        <a href="{{ route('blog.category', $category->slug) }}"
                           class="px-3 py-1 bg-gray-100 rounded-full text-sm hover:bg-gray-200">
                            {{ $category->name }}
                        </a>
                    @endforeach
                </div>
            @endif
        </header>

        <div class="prose max-w-none mb-12">
            {!! $post->content !!}
        </div>

        @if($post->tags->count())
            <div class="border-t border-gray-200 pt-6 mb-8">
                <div class="flex flex-wrap gap-2">
                    @foreach($post->tags as $tag)
                        <a href="{{ route('blog.tag', $tag->slug) }}"
                           class="px-3 py-1 bg-gray-100 rounded-full text-sm hover:bg-gray-200">
                            #{{ $tag->name }}
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        @if($post->comments_enabled)
            <section class="border-t border-gray-200 pt-8" x-data="{ showCommentForm: false }">
                <h2 class="text-2xl font-bold mb-8">Comments ({{ $post->comments->count() }})</h2>

                <button @click="showCommentForm = !showCommentForm"
                        class="mb-6 inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    <span x-text="showCommentForm ? 'Cancel' : 'Write a comment'"></span>
                </button>

                <div x-show="showCommentForm" x-transition class="mb-8">
                    @include('frontend.partials.comment-form')
                </div>

                <div class="space-y-8">
                    @foreach($post->comments->whereNull('parent_id') as $comment)
                        @include('frontend.partials.comment', ['comment' => $comment])
                    @endforeach
                </div>
            </section>
        @endif
    </article>

    @if($related_posts->count())
        <section class="bg-gray-50 py-12">
            <div class="max-w-7xl mx-auto px-4">
                <h2 class="text-2xl font-bold mb-8">Related Posts</h2>
                <div class="grid md:grid-cols-3 gap-6">
                    @foreach($related_posts as $post)
                        @include('frontend.partials.post-card', ['post' => $post])
                    @endforeach
                </div>
            </div>
        </section>
    @endif
@endsection
