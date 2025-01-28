{{-- resources/views/author/posts/index.blade.php --}}
@extends('layouts.app')
@section('title', 'My Posts')

@section('content')
    <div class="mb-6 flex justify-between items-center">
        <h1 class="text-2xl font-bold">My Posts</h1>
        <a href="{{ route('authorposts.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
            Create New Post
        </a>
    </div>

    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Title</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Comments</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Created</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
            </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
            @foreach($posts as $post)
                <tr>
                    <td class="px-6 py-4">
                        <div class="text-sm font-medium text-gray-900">{{ $post->title }}</div>
                    </td>
                    <td class="px-6 py-4">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                {{ $post->status === 'published' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ ucfirst($post->status) }}
                            </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        {{ $post->comments_count ?? 0 }}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        {{ $post->created_at->format('M d, Y') }}
                    </td>
                    <td class="px-6 py-4 text-sm font-medium">
                        <a href="{{ route('author.posts.edit', $post) }}" class="text-blue-600 hover:text-blue-900 mr-3">Edit</a>
                        <a href="{{ route('blog.show', $post->slug) }}" class="text-green-600 hover:text-green-900">View</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <div class="px-6 py-4">
            {{ $posts->links() }}
        </div>
    </div>
@endsection
