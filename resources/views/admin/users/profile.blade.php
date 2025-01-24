@extends('layouts.admin')
@section('title', 'My Profile')
@section('header', 'My Profile')

@section('content')
    <div class="max-w-2xl">
        <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data" class="bg-white shadow-sm rounded-lg p-6">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <x-input-label for="name" value="Name" />
                <x-text-input id="name" type="text" name="name" :value="old('name', auth()->user()->name)" class="block mt-1 w-full" required />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <div class="mb-4">
                <x-input-label for="email" value="Email" />
                <x-text-input id="email" type="email" name="email" :value="old('email', auth()->user()->email)" class="block mt-1 w-full" required />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div class="mb-4">
                <x-input-label for="username" value="Username" />
                <x-text-input id="username" type="text" name="username" :value="old('username', auth()->user()->username)" class="block mt-1 w-full" required />
                <x-input-error :messages="$errors->get('username')" class="mt-2" />
            </div>

            <div class="mb-4">
                <x-input-label for="current_password" value="Current Password (required for password change)" />
                <x-text-input id="current_password" type="password" name="current_password" class="block mt-1 w-full" />
                <x-input-error :messages="$errors->get('current_password')" class="mt-2" />
            </div>

            <div class="mb-4">
                <x-input-label for="new_password" value="New Password (leave blank to keep current)" />
                <x-text-input id="new_password" type="password" name="new_password" class="block mt-1 w-full" />
                <x-input-error :messages="$errors->get('new_password')" class="mt-2" />
            </div>

            <div class="mb-4">
                <x-input-label for="avatar" value="Avatar" />
                @if(auth()->user()->avatar)
                    <div class="mb-2">
                        <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="" class="h-20 w-20 rounded-full">
                    </div>
                @endif
                <input type="file" id="avatar" name="avatar" class="block mt-1" accept="image/*">
                <x-input-error :messages="$errors->get('avatar')" class="mt-2" />
            </div>

            <div class="mb-4">
                <x-input-label for="bio" value="Bio" />
                <textarea id="bio" name="bio" rows="3" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">{{ old('bio', auth()->user()->bio) }}</textarea>
                <x-input-error :messages="$errors->get('bio')" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end">
                <x-primary-button>Update Profile</x-primary-button>
            </div>
        </form>
    </div>
@endsection
