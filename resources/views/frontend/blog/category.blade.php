@extends('layouts.app')
@section('title', $category->name)

@section('content')
    <div class="max-w-7xl mx-auto px-4 py-8">
        <header class="mb-8">
            <h1 class="text-3xl font-bold">{{ $category->name }}</h1>
            @if($category->description)
                <p class="text-gray-600 mt-2">{{ $category->description }}</p>
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
            <p class="text-center text-gray-500 py-12">No posts found in this category.</p>
        @endif
    </div>
@endsection
