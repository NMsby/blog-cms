<article class="bg-white rounded-lg shadow-lg overflow-hidden">
    @if($post->featured_image)
        <a href="{{ route('blog.show', $post->slug) }}">
            <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}" class="w-full h-48 object-cover">
        </a>
    @endif
    <div class="p-6">
        <h3 class="text-xl font-bold mb-2">
            <a href="{{ route('blog.show', $post->slug) }}" class="hover:text-blue-600">{{ $post->title }}</a>
        </h3>
        @if($post->excerpt)
            <p class="text-gray-600 mb-4">{{ Str::limit($post->excerpt) }}</p>
        @endif
        <div class="flex items-center justify-between text-sm text-gray-500">
            <div class="flex items-center">
                <span>{{ $post->user->name }}</span>
                <span class="mx-2">&bull;</span>
                <span>{{ $post->created_at->format('M d, Y') }}</span>
            </div>
            <span>{{ $post->reading_time }} min read</span>
        </div>
    </div>
</article>
