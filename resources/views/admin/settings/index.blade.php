@extends('layouts.admin')
@section('title', 'Settings')
@section('header', 'Settings')

@section('content')
    <form action="{{ route('admin.settings.update') }}" method="POST" class="max-w-4xl">
        @csrf
        <div class="bg-white shadow-sm rounded-lg p-6">
            @foreach($settings as $group => $groupSettings)
                <div class="mb-8">
                    <h3 class="text-lg font-semibold mb-4">{{ ucfirst($group) }} Settings</h3>
                    <div class="space-y-4">
                        @foreach($groupSettings as $setting)
                            <div class="mb-4">
                                <x-input-label :for="$setting->key" :value="ucwords(str_replace('_', ' ', $setting->key))" />

                                @if($setting->type === 'text')
                                    <x-text-input :id="$setting->key" type="text" :name="'settings[' . $setting->key . ']'" :value="old('settings.' . $setting->key, $setting->value)" class="block mt-1 w-full" />
                                @elseif($setting->type === 'textarea')
                                    <textarea id="$setting->key" name="'settings[' . $setting->key . ']'" rows="3" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">{{ old('settings.' . $setting->key, $setting->value) }}</textarea>
                                @elseif($setting->type === 'boolean')
                                    <label class="mt-2 inline-flex items-center">
                                        <input type="checkbox" name="'settings[' . $setting->key . ']'" value="1" {{ old('settings.' . $setting->key, $setting->value) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        <span class="ml-2">Enabled</span>
                                    </label>
                                @endif

                                <x-input-error :messages="$errors->get('settings.' . $setting->key)" class="mt-2" />
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach

            <div class="mt-6 flex items-center justify-end gap-2">
                <form action="{{ route('admin.settings.clear-cache') }}" method="POST" class="inline">
                    @csrf
                    <x-secondary-button type="submit">Clear Cache</x-secondary-button>
                </form>
                <x-primary-button>Save Settings</x-primary-button>
            </div>
        </div>
    </form>
@endsection
