@extends('layouts.app')
@section('title', 'Blog')

@section('content')
    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="lg:flex lg:gap-8">
            <!-- Main Content -->
            <main class="lg:w-2/3">
                @if(request('search'))
                    <h2 class="text-2xl font-bold mb-6">Search Results for: {{ request('search') }}</h2>
                @endif

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
                    <div class="text-center py-12">
                        <p class="text-gray-500">No posts found.</p>
                    </div>
                @endif
            </main>

            <!-- Sidebar -->
            <aside class="lg:w-1/3 mt-8 lg:mt-0">
                <!-- Search -->
                <div class="bg-white p-6 rounded-lg shadow mb-6">
                    <form action="{{ route('blog.index') }}" method="GET">
                        <div class="flex">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search posts..."
                                   class="flex-1 rounded-l-md border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-r-md hover:bg-blue-600">Search</button>
                        </div>
                    </form>
                </div>

                <!-- Categories -->
                <div class="bg-white p-6 rounded-lg shadow mb-6">
                    <h3 class="text-lg font-semibold mb-4">Categories</h3>
                    <ul class="space-y-2">
                        @foreach($categories as $category)
                            <li>
                                <a href="{{ route('blog.category', $category->slug) }}"
                                   class="flex justify-between items-center hover:text-blue-600">
                                    <span>{{ $category->name }}</span>
                                    <span class="text-gray-500 text-sm">{{ $category->posts_count }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <!-- Popular Tags -->
                <div class="bg-white p-6 rounded-lg shadow">
                    <h3 class="text-lg font-semibold mb-4">Popular Tags</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($popular_tags as $tag)
                            <a href="{{ route('blog.tag', $tag->slug) }}"
                               class="bg-gray-100 px-3 py-1 rounded-full text-sm hover:bg-gray-200">
                                {{ $tag->name }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </aside>
        </div>
    </div>
@endsection
