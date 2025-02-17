{{-- resources/views/frontend/partials/post-card.blade.php --}}
<article class="bg-white rounded-lg shadow-lg overflow-hidden" x-data="{ showExcerpt: false }">
    @if($post->featured_image)
        <a href="{{ route('blog.show', ['post' => $post]) }}" class="block aspect-video">
            <img src="{{ asset('storage/' . $post->featured_image) }}"
                 alt="{{ $post->title }}"
                 class="w-full h-full object-cover">
        </a>
    @endif

    <div class="p-6">
        <header class="mb-4">
            <h2 class="text-xl font-bold">
                <a href="{{ route('blog.show', ['post' => $post]) }}"
                   class="hover:text-blue-600 transition-colors">
                    {{ $post->title }}
                </a>
            </h2>
            <div class="flex items-center text-sm text-gray-500 mt-2">
                <a href="{{ route('blog.author', $post->user->username) }}" class="hover:text-blue-600">{{  $post->user->name }}</a>
                <span class="mx-2">&middot;</span>
                <span>{{ $post->created_at->format('M d, Y') }}</span>
                <span class="mx-2">&middot;</span>
                <span>{{ $post->reading_time }} min read</span>
            </div>
        </header>

        @if($post->excerpt)
            <p class="text-gray-600 mb-4"
               x-show="showExcerpt"
               x-transition>{{ $post->excerpt }}</p>
        @endif

        <div class="flex items-center justify-between">
            <div class="flex gap-2">
                @foreach($post->categories as $category)
                    <a href="{{ route('blog.category', $category->slug) }}"
                       class="text-sm text-blue-600 hover:text-blue-800">
                        {{ $category->name }}
                    </a>
                @endforeach
            </div>
            @if($post->excerpt)
                <button @click="showExcerpt = !showExcerpt"
                        class="text-sm text-gray-500 hover:text-gray-700">
                    <span x-text="showExcerpt ? 'Show less' : 'Show more'"></span>
                </button>
            @endif
        </div>
    </div>
</article>
