@extends('layouts.admin')
@section('title', 'Create Tag')
@section('header', 'Create Tag')

@section('content')
    <form action="{{ route('admin.tags.store') }}" method="POST" class="max-w-2xl">
        @csrf
        <div class="bg-white shadow-sm rounded-lg p-6">
            <div class="mb-4">
                <x-input-label for="name" value="Name" />
                <x-text-input id="name" type="text" name="name" :value="old('name')" class="block mt-1 w-full" required />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <div class="mb-4">
                <x-input-label for="description" value="Description" />
                <textarea id="description" name="description" rows="3" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">{{ old('description') }}</textarea>
                <x-input-error :messages="$errors->get('description')" class="mt-2" />
            </div>

            <div class="mt-6 flex items-center justify-end">
                <x-secondary-button onclick="window.history.back()" class="mr-3">
                    Cancel
                </x-secondary-button>
                <x-primary-button>
                    Create Tag
                </x-primary-button>
            </div>
        </div>
    </form>
@endsection
