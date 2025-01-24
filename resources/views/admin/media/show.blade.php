@extends('layouts.admin')
@section('title', $media->original_filename)
@section('header', 'View Media')

@section('content')
    <div class="bg-white shadow-sm rounded-lg p-6">
        <div class="flex justify-between items-start mb-6">
            <div>
                <h2 class="text-2xl font-bold">{{ $media->original_filename }}</h2>
                <p class="text-gray-500">Uploaded by {{ $media->user->name }} on {{ $media->created_at->format('M d, Y H:i') }}</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.media.download', $media) }}" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Download</a>
                <form action="{{ route('admin.media.destroy', $media) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600" onclick="return confirm('Are you sure?')">Delete</button>
                </form>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                @if(str_starts_with($media->mime_type, 'image/'))
                    <img src="{{ asset('storage/' . $media->path) }}" alt="{{ $media->original_filename }}" class="max-w-full rounded">
                @else
                    <div class="bg-gray-100 p-12 rounded flex items-center justify-center">
                        <span class="text-6xl text-gray-400">
                            {{ strtoupper(pathinfo($media->original_filename, PATHINFO_EXTENSION)) }}
                        </span>
                    </div>
                @endif
            </div>

            <div>
                <h3 class="font-semibold mb-4">File Details</h3>
                <dl class="grid grid-cols-3 gap-4">
                    <dt class="text-gray-600">Filename</dt>
                    <dd class="col-span-2">{{ $media->original_filename }}</dd>

                    <dt class="text-gray-600">Type</dt>
                    <dd class="col-span-2">{{ $media->mime_type }}</dd>

                    <dt class="text-gray-600">Size</dt>
                    <dd class="col-span-2">{{ $media->size_for_humans }}</dd>

                    <dt class="text-gray-600">Path</dt>
                    <dd class="col-span-2">{{ $media->path }}</dd>

                    @if($media->additional_attributes)
                        @foreach($media->additional_attributes as $key => $value)
                            <dt class="text-gray-600">{{ ucfirst($key) }}</dt>
                            <dd class="col-span-2">
                                @if(is_array($value))
                                    {{ json_encode($value) }}
                                @else
                                    {{ $value }}
                                @endif
                            </dd>
                        @endforeach
                    @endif
                </dl>
            </div>
        </div>
    </div>
@endsection
