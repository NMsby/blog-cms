{{-- resources/views/frontend/blog/category.blade.php --}}
@extends('layouts.app')
@section('title', $category->name)

@section('content')
    <div class="max-w-7xl mx-auto px-4 py-8">
        <header class="mb-8">
            <h1 class="text-3xl font-bold mb-2">{{ $category->name }}</h1>
            @if($category->description)
                <p class="text-gray-600">{{ $category->description }}</p>
            @endif
            <div class="mt-4 text-sm text-gray-500">
                {{ $posts->total() }} {{ Str::plural('post', $posts->total()) }}
            </div>
        </header>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($posts as $post)
                @include('frontend.partials.post-card', ['post' => $post])
            @empty
                <div class="col-span-full text-center py-12 bg-white rounded-lg shadow">
                    <p class="text-gray-500">No posts found in this category.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-8">
            {{ $posts->links() }}
        </div>
    </div>
@endsection
