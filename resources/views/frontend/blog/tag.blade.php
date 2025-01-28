{{-- resources/views/frontend/blog/tag.blade.php --}}
@extends('layouts.app')
@section('title', "#$tag->name")

@section('content')
    <div class="max-w-7xl mx-auto px-4 py-8">
        <header class="mb-8">
            <div class="flex items-center gap-2 mb-2">
                <h1 class="text-3xl font-bold">#{{ $tag->name }}</h1>
                <span class="px-3 py-1 bg-gray-100 rounded-full text-sm">
                {{ $posts->total() }} {{ Str::plural('post', $posts->total()) }}
            </span>
            </div>
            @if($tag->description)
                <p class="text-gray-600">{{ $tag->description }}</p>
            @endif
        </header>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($posts as $post)
                @include('frontend.partials.post-card', ['post' => $post])
            @empty
                <div class="col-span-full text-center py-12 bg-white rounded-lg shadow">
                    <p class="text-gray-500">No posts found with this tag.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-8">
            {{ $posts->links() }}
        </div>
    </div>
@endsection
