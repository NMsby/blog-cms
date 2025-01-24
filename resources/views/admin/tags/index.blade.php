@extends('layouts.admin')
@section('title', 'Tags')
@section('header', 'Tags')

@section('content')
    <div class="mb-4 flex justify-between items-center">
        <h2 class="text-2xl font-bold">Tags</h2>
        <a href="{{ route('admin.tags.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
            Add Tag
        </a>
    </div>

    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Slug</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Posts</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
            </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
            @foreach($tags as $tag)
                <tr>
                    <td class="px-6 py-4">{{ $tag->name }}</td>
                    <td class="px-6 py-4">{{ $tag->slug }}</td>
                    <td class="px-6 py-4">{{ $tag->posts_count }}</td>
                    <td class="px-6 py-4 text-sm font-medium">
                        <a href="{{ route('admin.tags.edit', $tag) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                        <form action="{{ route('admin.tags.destroy', $tag) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div class="px-6 py-4">
            {{ $tags->links() }}
        </div>
    </div>
@endsection
