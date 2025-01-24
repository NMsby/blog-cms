@extends('layouts.admin')
@section('title', 'Create Category')
@section('header', 'Create Category')

@section('content')
    <form action="{{ route('admin.categories.store') }}" method="POST" class="max-w-2xl">
        @csrf
        <div class="bg-white shadow-sm rounded-lg p-6">
            <div class="mb-4">
                <x-input-label for="name" value="Name" />
                <x-text-input id="name" type="text" name="name" :value="old('name')" class="block mt-1 w-full" required />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <div class="mb-4">
                <x-input-label for="parent_id" value="Parent Category" />
                <select id="parent_id" name="parent_id" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <option value="">None</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('parent_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('parent_id')" class="mt-2" />
            </div>

            <div class="mb-4">
                <x-input-label for="description" value="Description" />
                <textarea id="description" name="description" rows="3" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">{{ old('description') }}</textarea>
                <x-input-error :messages="$errors->get('description')" class="mt-2" />
            </div>

            <div class="mb-4">
                <x-input-label for="meta_title" value="Meta Title" />
                <x-text-input id="meta_title" type="text" name="meta_title" :value="old('meta_title')" class="block mt-1 w-full" />
                <x-input-error :messages="$errors->get('meta_title')" class="mt-2" />
            </div>

            <div class="mb-4">
                <x-input-label for="meta_description" value="Meta Description" />
                <textarea id="meta_description" name="meta_description" rows="2" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">{{ old('meta_description') }}</textarea>
                <x-input-error :messages="$errors->get('meta_description')" class="mt-2" />
            </div>

            <div class="mb-4">
                <label class="flex items-center">
                    <input type="checkbox" name="is_visible" value="1" {{ old('is_visible', true) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <span class="ml-2">Visible</span>
                </label>
            </div>

            <div class="mt-6 flex items-center justify-end">
                <x-secondary-button onclick="window.history.back()" class="mr-3">
                    Cancel
                </x-secondary-button>
                <x-primary-button>
                    Create Category
                </x-primary-button>
            </div>
        </div>
    </form>
@endsection
