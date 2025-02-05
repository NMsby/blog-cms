@extends('layouts.app')

@section('title', 'Edit Profile')

@section('content')
    <div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow-sm rounded-lg">
            <div class="p-6">
                <h2 class="text-2xl font-bold mb-6">Edit Profile</h2>

                <form action="{{ route('authorprofile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="space-y-6">
                        <!-- Avatar Section -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Profile Picture</label>
                            <div class="flex items-center space-x-6">
                                <div class="shrink-0">
                                    @if($user->avatar)
                                        <img class="h-16 w-16 object-cover rounded-full"
                                             src="{{ asset('storage/' . $user->avatar) }}"
                                             alt="{{ $user->name }}">
                                    @else
                                        <div class="h-16 w-16 rounded-full bg-gray-200 flex items-center justify-center">
                                            <span class="text-gray-500 text-xl">{{ substr($user->name, 0, 1) }}</span>
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <input type="file" name="avatar" id="avatar"
                                           class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4
                                           file:rounded-full file:border-0 file:text-sm file:font-semibold
                                           file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"/>
                                    <p class="mt-1 text-sm text-gray-500">PNG, JPG, GIF up to 2MB</p>
                                </div>
                                @if($user->avatar)
                                    <button type="button"
                                            onclick="document.getElementById('remove-avatar-form').submit()"
                                            class="text-sm text-red-600 hover:text-red-800">Remove</button>
                                @endif
                            </div>
                        </div>

                        <!-- Basic Information -->
                        <div class="grid grid-cols-1 gap-6 mt-4 sm:grid-cols-2">
                            <div>
                                <x-input-label for="name" value="Name" />
                                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                                              :value="old('name', $user->name)" required />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="username" value="Username" />
                                <x-text-input id="username" name="username" type="text" class="mt-1 block w-full"
                                              :value="old('username', $user->username)" required />
                                <x-input-error :messages="$errors->get('username')" class="mt-2" />
                            </div>

                            <div class="sm:col-span-2">
                                <x-input-label for="email" value="Email" />
                                <x-text-input id="email" name="email" type="email" class="mt-1 block w-full"
                                              :value="old('email', $user->email)" required />
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>

                            <div class="sm:col-span-2">
                                <x-input-label for="bio" value="Bio" />
                                <textarea id="bio" name="bio" rows="4"
                                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm
                                          focus:border-indigo-300 focus:ring focus:ring-indigo-200
                                          focus:ring-opacity-50">{{ old('bio', $user->bio) }}</textarea>
                                <x-input-error :messages="$errors->get('bio')" class="mt-2" />
                            </div>

                            <div class="sm:col-span-2">
                                <x-input-label for="website" value="Website" />
                                <x-text-input id="website" name="website" type="url" class="mt-1 block w-full"
                                              :value="old('website', $user->website)" />
                                <x-input-error :messages="$errors->get('website')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Social Links -->
                        <div class="border-t border-gray-200 pt-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Social Links</h3>
                            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                                <div>
                                    <x-input-label for="twitter" value="Twitter" />
                                    <x-text-input id="twitter" name="twitter" type="text" class="mt-1 block w-full"
                                                  :value="old('twitter', $user->social_links['twitter'] ?? '')" />
                                    <x-input-error :messages="$errors->get('twitter')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="facebook" value="Facebook" />
                                    <x-text-input id="facebook" name="facebook" type="text" class="mt-1 block w-full"
                                                  :value="old('facebook', $user->social_links['facebook'] ?? '')" />
                                    <x-input-error :messages="$errors->get('facebook')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="linkedin" value="LinkedIn" />
                                    <x-text-input id="linkedin" name="linkedin" type="text" class="mt-1 block w-full"
                                                  :value="old('linkedin', $user->social_links['linkedin'] ?? '')" />
                                    <x-input-error :messages="$errors->get('linkedin')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <!-- Change Password -->
                        <div class="border-t border-gray-200 pt-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Change Password</h3>
                            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                                <div class="sm:col-span-2">
                                    <x-input-label for="current_password" value="Current Password" />
                                    <x-text-input id="current_password" name="current_password" type="password"
                                                  class="mt-1 block w-full" />
                                    <x-input-error :messages="$errors->get('current_password')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="new_password" value="New Password" />
                                    <x-text-input id="new_password" name="new_password" type="password"
                                                  class="mt-1 block w-full" />
                                    <x-input-error :messages="$errors->get('new_password')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="new_password_confirmation" value="Confirm New Password" />
                                    <x-text-input id="new_password_confirmation" name="new_password_confirmation"
                                                  type="password" class="mt-1 block w-full" />
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end gap-4">
                            <x-secondary-button onclick="window.history.back()">
                                Cancel
                            </x-secondary-button>
                            <x-primary-button>
                                Save Changes
                            </x-primary-button>
                        </div>
                    </div>
                </form>

                <!-- Hidden form for avatar removal -->
                <form id="remove-avatar-form" action="{{ route('authorprofile.remove-avatar') }}" method="POST" class="hidden">
                    @csrf
                    @method('DELETE')
                </form>
            </div>
        </div>
    </div>
@endsection
