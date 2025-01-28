@extends('layouts.app')
@section('title', 'Create Post')
@section('header', 'Create Post')

@section('content')
    <form action="{{ route('authorposts.store') }}" method="POST" enctype="multipart/form-data" class="max-w-4xl">
        @csrf
        <div class="bg-white shadow-sm rounded-lg p-6">
            <div class="mb-4">
                <x-input-label for="title" value="Title" />
                <x-text-input id="title" type="text" name="title" :value="old('title')" class="block mt-1 w-full" required />
                <x-input-error :messages="$errors->get('title')" class="mt-2" />
            </div>

            <div class="mb-4">
                <x-input-label for="content" value="Content" />
                <textarea id="content" name="content" rows="10" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>{{ old('content') }}</textarea>
                <x-input-error :messages="$errors->get('content')" class="mt-2" />
            </div>

            <div class="mb-4">
                <x-input-label for="excerpt" value="Excerpt" />
                <textarea id="excerpt" name="excerpt" rows="3" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">{{ old('excerpt') }}</textarea>
                <x-input-error :messages="$errors->get('excerpt')" class="mt-2" />
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="mb-4">
                    <x-input-label for="category_ids" value="Categories" />
                    <select id="category_ids" name="category_ids[]" multiple class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" size="4" required>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ in_array($category->id, old('category_ids', [])) ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('category_ids')" class="mt-2" />
                </div>

                <div class="mb-4">
                    <x-input-label for="tag_ids" value="Tags" />
                    <select id="tag_ids" name="tag_ids[]" multiple class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" size="4">
                        @foreach($tags as $tag)
                            <option value="{{ $tag->id }}" {{ in_array($tag->id, old('tag_ids', [])) ? 'selected' : '' }}>
                                {{ $tag->name }}
                            </option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('tag_ids')" class="mt-2" />
                </div>
            </div>

            <div class="mb-4">
                <x-input-label for="featured_image" value="Featured Image" />
                <input type="file" id="featured_image" name="featured_image" class="block mt-1" accept="image/*">
                <x-input-error :messages="$errors->get('featured_image')" class="mt-2" />
            </div>

            <div class="mb-4">
                <x-input-label for="status" value="Status" />
                <select id="status" name="status" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                    <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="published" {{ old('status') === 'published' ? 'selected' : '' }}>Published</option>
                </select>
                <x-input-error :messages="$errors->get('status')" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end gap-4">
                <x-secondary-button onclick="window.history.back()">Cancel</x-secondary-button>
                <x-primary-button>Create Post</x-primary-button>
            </div>
        </div>
    </form>

    @push('scripts')
        <script>
            tinymce.init({
                selector: '#content',
                plugins: ['link', 'lists', 'image'],
                toolbar: 'undo redo | formatselect | bold italic | alignleft aligncenter alignright | bullist numlist | link image',
                height: 400
            });
        </script>
    @endpush
@endsection
