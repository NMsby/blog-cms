{{-- resources/views/frontend/partials/sidebar.blade.php --}}
<div class="space-y-6">
    <!-- Categories Widget -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold mb-4">Categories</h3>
        <ul class="space-y-2">
            @foreach($categories as $category)
                <li>
                    <a href="{{ route('blog.category', $category->slug) }}"
                       class="flex justify-between items-center text-gray-600 hover:text-blue-600">
                        <span>{{ $category->name }}</span>
                        <span class="text-sm text-gray-500">{{ $category->posts_count }}</span>
                    </a>
                </li>
            @endforeach
        </ul>
    </div>

    <!-- Popular Tags Widget -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold mb-4">Popular Tags</h3>
        <div class="flex flex-wrap gap-2">
            @foreach($popular_tags as $tag)
                <a href="{{ route('blog.tag', $tag->slug) }}"
                   class="px-3 py-1 bg-gray-100 rounded-full text-sm text-gray-600 hover:bg-gray-200">
                    {{ $tag->name }}
                </a>
            @endforeach
        </div>
    </div>

    <!-- Popular Authors Widget -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold mb-4">Popular Authors</h3>
        <div class="space-y-4">
            @foreach($popular_authors as $author)
                <a href="{{ route('blog.author', $author->username) }}"
                   class="flex items-center space-x-3 group">
                    <div class="flex-shrink-0 w-10 h-10 rounded-full overflow-hidden">
                        @if($author->avatar)
                            <img src="{{ asset('storage/' . $author->avatar) }}"
                                 alt="{{ $author->name }}"
                                 class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-gray-100 text-gray-500">
                                {{ substr($author->name, 0, 1) }}
                            </div>
                        @endif
                    </div>
                    <div class="flex-1">
                        <p class="font-medium group-hover:text-blue-600">{{ $author->name }}</p>
                        <p class="text-sm text-gray-500">{{ $author->posts_count }} posts</p>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</div>
