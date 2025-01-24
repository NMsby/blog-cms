@extends('layouts.admin')
@section('title', 'Create User')
@section('header', 'Create User')

@section('content')
    <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data" class="max-w-2xl">
        @csrf
        <div class="bg-white shadow-sm rounded-lg p-6">
            <div class="mb-4">
                <x-input-label for="name" value="Name" />
                <x-text-input id="name" type="text" name="name" :value="old('name')" class="block mt-1 w-full" required />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <div class="mb-4">
                <x-input-label for="email" value="Email" />
                <x-text-input id="email" type="email" name="email" :value="old('email')" class="block mt-1 w-full" required />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div class="mb-4">
                <x-input-label for="username" value="Username" />
                <x-text-input id="username" type="text" name="username" :value="old('username')" class="block mt-1 w-full" required />
                <x-input-error :messages="$errors->get('username')" class="mt-2" />
            </div>

            <div class="mb-4">
                <x-input-label for="password" value="Password" />
                <x-text-input id="password" type="password" name="password" class="block mt-1 w-full" required />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div class="mb-4">
                <x-input-label for="role" value="Role" />
                <select id="role" name="role" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <option value="author" {{ old('role') === 'author' ? 'selected' : '' }}>Author</option>
                    <option value="editor" {{ old('role') === 'editor' ? 'selected' : '' }}>Editor</option>
                    <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                </select>
                <x-input-error :messages="$errors->get('role')" class="mt-2" />
            </div>

            <div class="mb-4">
                <x-input-label for="avatar" value="Avatar" />
                <input type="file" id="avatar" name="avatar" class="block mt-1" accept="image/*">
                <x-input-error :messages="$errors->get('avatar')" class="mt-2" />
            </div>

            <div class="mb-4">
                <x-input-label for="bio" value="Bio" />
                <textarea id="bio" name="bio" rows="3" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">{{ old('bio') }}</textarea>
                <x-input-error :messages="$errors->get('bio')" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end gap-2">
                <x-secondary-button onclick="window.history.back()">Cancel</x-secondary-button>
                <x-primary-button>Create User</x-primary-button>
            </div>
        </div>
    </form>
@endsection
