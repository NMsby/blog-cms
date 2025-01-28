{{-- resources/views/frontend/blog/index.blade.php --}}
@extends('layouts.app')
@section('title', 'Blog')

@section('content')
    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="lg:flex lg:gap-8">
            <!-- Main Content -->
            <main class="lg:w-2/3">
                <div x-data="{ showSearch: false }">
                    <div class="mb-6 flex justify-between items-center">
                        <h1 class="text-2xl font-bold">Latest Posts</h1>
                        <button @click="showSearch = !showSearch" class="text-gray-500 hover:text-gray-700">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </button>
                    </div>

                    <div x-show="showSearch" x-transition class="mb-6">
                        <form action="{{ route('blog.index') }}" method="GET">
                            <input type="text" name="search" placeholder="Search posts..." value="{{ request('search') }}"
                                   class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                        </form>
                    </div>
                </div>

                @if($posts->count() > 0)
                    <div class="space-y-8">
                        @foreach($posts as $post)
                            @include('frontend.partials.post-card', ['post' => $post])
                        @endforeach
                    </div>
                    <div class="mt-8">
                        {{ $posts->links() }}
                    </div>
                @else
                    <div class="text-center py-12 bg-white rounded-lg shadow">
                        <p class="text-gray-500">No posts found.</p>
                    </div>
                @endif
            </main>

            <!-- Sidebar -->
            <aside class="lg:w-1/3 mt-8 lg:mt-0">
                @include('frontend.partials.sidebar')
            </aside>
        </div>
    </div>
@endsection
