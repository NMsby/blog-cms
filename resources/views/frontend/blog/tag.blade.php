@extends('layouts.app')
@section('title', "Posts tagged with '$tag->name'")

@section('content')
    <div class="max-w-7xl mx-auto px-4 py-8">
        <header class="mb-8">
            <h1 class="text-3xl font-bold">Posts tagged with '{{ $tag->name }}'</h1>
            @if($tag->description)
                <p class="text-gray-600 mt-2">{{ $tag->description }}</p>
            @endif
        </header>

        @if($posts->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($posts as $post)
                    @include('frontend.partials.post-card', ['post' => $post])
                @endforeach
            </div>
            <div class="mt-8">
                {{ $posts->links() }}
            </div>
        @else
            <p class="text-center text-gray-500 py-12">No posts found with this tag.</p>
        @endif
    </div>
@endsection
