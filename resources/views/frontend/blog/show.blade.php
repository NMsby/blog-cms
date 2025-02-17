@extends('layouts.app')
@section('title', $post->title)

@section('content')
    <article class="max-w-4xl mx-auto px-4 py-4">
        <header class="mb-8">
            <h1 class="text-4xl font-bold mb-4">{{ $post->title }}</h1>

            <div class="flex flex-wrap items-center gap-4 text-gray-600 mb-6">
                <a href="{{ route('blog.author', $post->user->username) }}"
                   class="flex items-center group hover:bg-gray-50 rounded-lg p-2 -m-2 transition-colors">
                    <div class="w-12 h-12 rounded-full overflow-hidden bg-gray-100 flex-shrink-0">
                        @if($post->user->avatar)
                            <img src="{{ asset('storage/' . $post->user->avatar) }}"
                                 alt="{{ $post->user->name }}"
                                 class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-500 text-xl">
                                {{ substr($post->user->name, 0, 1) }}
                            </div>
                        @endif
                    </div>
                    <div class="ml-3">
                        <div class="font-medium group-hover:text-blue-600 transition-colors">{{ $post->user->name }}</div>
                        <div class="text-sm text-gray-500">{{ '@' . $post->user->username }}</div>
                    </div>
                </a>
                <span>&middot;</span>
                <time datetime="{{ $post->published_at ? $post->published_at->format('Y-m-d') : '' }}">
                    {{ $post->published_at ? $post->published_at->format('M d, Y') : 'Draft' }}
                </time>
                <span>&middot;</span>
                <span>{{ $post->reading_time }} min read</span>
                <span>&middot;</span>
                <div class="flex items-center text-gray-500">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    {{ $post->view_count }} views
                </div>
                <!-- Social Media Share buttons -->
                <div class="flex gap-4 mt-6">
                    <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}" target="_blank" class="text-blue-400 hover:text-blue-600">
                        <!-- Twitter icon -->
                    </a>
                </div>
            </div>

            @if($post->categories->count())
                <div class="flex flex-wrap gap-2 mb-4">
                    @foreach($post->categories as $category)
                        <a href="{{ route('blog.category', $category->slug) }}"
                           class="px-3 py-1 bg-gray-500 rounded-full text-sm">
                            {{ $category->name }}
                        </a>
                    @endforeach
                </div>
            @endif

            @if($post->featured_image)
                <div class="rounded-xl overflow-hidden mb-8 aspect-video">
                    <img src="{{ asset('storage/' . $post->featured_image) }}"
                         alt="{{ $post->title }}"
                         class="w-full h-full object-cover">
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
                <h2 class="text-2xl font-bold mb-8">
                    Comments (<span class="comment-count">{{ $post->comments->count() }}</span>)
                </h2>

                <button @click="showCommentForm = !showCommentForm"
                        class="mb-6 inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    <span x-text="showCommentForm ? 'Cancel' : 'Write a comment'"></span>
                </button>

                <div x-show="showCommentForm" x-transition class="mb-8">
                    @include('frontend.partials.comment-form')
                </div>

                <div class="comments-container space-y-8">
                    @foreach($post->comments->where('status', App\Models\Comment::STATUS_APPROVED)->whereNull('parent_id') as $comment)
                        <div id="comment-{{ $comment->id }}" class="comment">
                            @include('frontend.partials.comment', ['comment' => $comment])
                        </div>
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
