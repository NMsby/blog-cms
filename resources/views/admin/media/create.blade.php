@extends('layouts.admin')
@section('title', 'Upload Files')
@section('header', 'Upload Files')

@section('content')
    <div class="max-w-2xl">
        <form action="{{ route('admin.media.store') }}" method="POST" enctype="multipart/form-data" class="bg-white shadow-sm rounded-lg p-6">
            @csrf
            <div class="mb-4">
                <x-input-label for="files" value="Choose Files" />
                <input type="file" id="files" name="files[]" multiple class="mt-1 block w-full" required accept="image/*,.pdf,.doc,.docx,.xls,.xlsx">
                <x-input-error :messages="$errors->get('files.*')" class="mt-2" />
                <p class="mt-1 text-sm text-gray-500">Max file size: 10MB. Allowed types: Images, PDF, DOC, DOCX, XLS, XLSX</p>
            </div>

            <div class="mb-4">
                <x-input-label for="folder" value="Folder (Optional)" />
                <x-text-input id="folder" type="text" name="folder" :value="old('folder')" class="block mt-1 w-full" placeholder="e.g., posts/2024" />
                <x-input-error :messages="$errors->get('folder')" class="mt-2" />
            </div>

            <div class="flex justify-end gap-2">
                <x-secondary-button onclick="window.history.back()">Cancel</x-secondary-button>
                <x-primary-button>Upload Files</x-primary-button>
            </div>
        </form>
    </div>
@endsection
