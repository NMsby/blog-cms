@extends('layouts.admin')
@section('title', 'Media')
@section('header', 'Media')

@section('content')
    <div class="mb-4 flex justify-between items-center">
        <h2 class="text-2xl font-bold">Media Library</h2>
        <a href="{{ route('admin.media.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
            Upload Files
        </a>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
        @foreach($media as $file)
            <div class="bg-white rounded-lg shadow overflow-hidden">
                @if(str_starts_with($file->mime_type, 'image/'))
                    <img src="{{ asset('storage/' . $file->path) }}" alt="{{ $file->original_filename }}" class="w-full h-32 object-cover">
                @else
                    <div class="w-full h-32 bg-gray-100 flex items-center justify-center">
                        <span class="text-4xl text-gray-400">
                            {{ strtoupper(pathinfo($file->original_filename, PATHINFO_EXTENSION)) }}
                        </span>
                    </div>
                @endif
                <div class="p-3">
                    <p class="text-sm font-medium truncate" title="{{ $file->original_filename }}">
                        {{ $file->original_filename }}
                    </p>
                    <p class="text-xs text-gray-500">{{ $file->size_for_humans }}</p>
                    <div class="mt-2 flex gap-2">
                        <a href="{{ route('admin.media.show', $file) }}" class="text-xs text-blue-600 hover:text-blue-900">View</a>
                        <a href="{{ route('admin.media.download', $file) }}" class="text-xs text-green-600 hover:text-green-900">Download</a>
                        <form action="{{ route('admin.media.destroy', $file) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-xs text-red-600 hover:text-red-900" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-4">
        {{ $media->links() }}
    </div>
@endsection
