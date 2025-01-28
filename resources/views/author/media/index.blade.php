{{-- resources/views/author/media/index.blade.php --}}
@extends('layouts.app')
@section('title', 'Media Library')
@section('header', 'Media Library')

@section('content')
    <div class="mb-4 flex justify-between items-center">
        <h2 class="text-2xl font-bold">Your Media Files</h2>
        <label for="upload-files" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 cursor-pointer">
            Upload Files
        </label>
        <form id="upload-form" action="{{ route('author.media.store') }}" method="POST" enctype="multipart/form-data" class="hidden">
            @csrf
            <input type="file" id="upload-files" name="files[]" multiple onchange="this.form.submit()"
                   accept="image/*,.pdf,.doc,.docx,.xls,.xlsx">
        </form>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
        @foreach($media as $file)
            <div class="bg-white rounded-lg shadow overflow-hidden">
                @if(str_starts_with($file->mime_type, 'image/'))
                    <img src="{{ asset('storage/' . $file->path) }}"
                         alt="{{ $file->original_filename }}"
                         class="w-full h-32 object-cover">
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
                        <a href="{{ asset('storage/' . $file->path) }}"
                           target="_blank"
                           class="text-xs text-blue-600 hover:text-blue-900">View</a>
                        <button onclick="copyToClipboard('{{ asset('storage/' . $file->path) }}')"
                                class="text-xs text-green-600 hover:text-green-900">Copy URL</button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-4">
        {{ $media->links() }}
    </div>

    @push('scripts')
        <script>
            function copyToClipboard(text) {
                navigator.clipboard.writeText(text).then(() => {
                    alert('URL copied to clipboard!');
                });
            }
        </script>
    @endpush
@endsection
