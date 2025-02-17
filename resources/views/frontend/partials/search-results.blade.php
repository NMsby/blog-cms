<div class="mt-4">
    <!-- Posts -->
    <div x-show="suggestions.posts.length" class="mb-6">
        <h3 class="text-sm font-semibold text-gray-500 mb-2">Posts</h3>
        <div class="divide-y">
            <template x-for="post in suggestions.posts" :key="post.id">
                <a :href="`/blog/${post.slug}`"
                   class="block py-2 px-3 hover:bg-gray-100 rounded transition">
                    <div x-text="post.title" class="font-medium"></div>
                    <div class="text-sm text-gray-500" x-text="post.author"></div>
                </a>
            </template>
        </div>
    </div>

    <!-- Authors -->
    <div x-show="suggestions.authors.length">
        <h3 class="text-sm font-semibold text-gray-500 mb-2">Authors</h3>
        <div class="divide-y">
            <template x-for="author in suggestions.authors" :key="author.id">
                <a :href="`/author/${author.username}`"
                   class="flex items-center py-2 px-3 hover:bg-gray-100 rounded transition">
                    <div class="w-8 h-8 rounded-full bg-gray-200 flex-shrink-0 mr-3">
                        <img x-show="author.avatar" :src="author.avatar"
                             class="w-full h-full rounded-full object-cover">
                        <div x-show="!author.avatar"
                             class="w-full h-full rounded-full flex items-center justify-center text-gray-500"
                             x-text="author.name.charAt(0)"></div>
                    </div>
                    <div>
                        <div x-text="author.name" class="font-medium"></div>
                        <div class="text-sm text-gray-500" x-text="`${author.posts_count} posts`"></div>
                    </div>
                </a>
            </template>
        </div>
    </div>
</div>
