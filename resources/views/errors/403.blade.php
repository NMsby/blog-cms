{{-- resources/views/errors/403.blade.php --}}
@extends('layouts.app')

@section('content')
    <div class="min-h-screen flex items-center justify-center">
        <div class="max-w-xl w-full px-6 py-8 bg-white shadow-md rounded-lg">
            <div class="text-center">
                <h1 class="text-4xl font-bold text-red-600 mb-4">403</h1>
                <h2 class="text-2xl font-semibold text-gray-800 mb-4">Access Denied</h2>
                <p class="text-gray-600 mb-6">Sorry, you don't have permission to access this area.</p>
                <a href="{{ route('home') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Return Home
                </a>
            </div>
        </div>
    </div>
@endsection
