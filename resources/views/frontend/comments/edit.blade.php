@extends('layouts.app')

@section('content')
    <div class="max-w-2xl mx-auto py-8">
        <div class="bg-white shadow rounded-lg p-6">
            <h2 class="text-lg font-medium mb-6">Edit Comment</h2>

            <form action="{{ route('blog.comments.update', $comment) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="content" class="block text-sm font-medium text-gray-700">Comment</label>
                    <textarea
                        name="content"
                        id="content"
                        rows="4"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200"
                        required
                    >{{ old('content', $comment->content) }}</textarea>
                    @error('content')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end space-x-3">
                    <a href="{{ url()->previous() }}" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                        Cancel
                    </a>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md text-sm font-medium hover:bg-blue-700">
                        Update Comment
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
