@extends('layouts.admin')
@section('title', $post->title)
@section('header', 'View Post')

@section('content')
    <div class="bg-white shadow-sm rounded-lg p-6">
        <div class="mb-6">
            <div class="flex justify-between items-center mb-4">
                <h1 class="text-2xl font-bold">{{ $post->title }}</h1>
                <div class="flex space-x-2">
                    <a href="{{ route('admin.posts.edit', $post) }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Edit</a>
                    @if($post->status === 'published')
                        <a href="{{ route('blog.show', $post->slug) }}" target="_blank" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">View Live</a>
                    @endif
                </div>
            </div>

            <div class="text-sm text-gray-500 mb-4">
                <p>Author: {{ $post->user->name }} | Created: {{ $post->created_at->format('M d, Y H:i') }}</p>
                <p>Status: <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $post->status === 'published' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                    {{ ucfirst($post->status) }}
                </span></p>
            </div>

            @if($post->featured_image)
                <img src="{{ asset('storage/' . $post->featured_image) }}" alt="Featured Image" class="w-full h-64 object-cover rounded mb-4">
            @endif

            <div class="prose max-w-none mb-4">
                {!! $post->content !!}
            </div>

            <div class="border-t pt-4">
                <h3 class="font-semibold mb-2">Categories:</h3>
                <div class="flex gap-2">
                    @foreach($post->categories as $category)
                        <span class="bg-gray-100 px-2 py-1 rounded">{{ $category->name }}</span>
                    @endforeach
                </div>
            </div>

            <div class="border-t pt-4 mt-4">
                <h3 class="font-semibold mb-2">Tags:</h3>
                <div class="flex gap-2">
                    @foreach($post->tags as $tag)
                        <span class="bg-gray-100 px-2 py-1 rounded">{{ $tag->name }}</span>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
