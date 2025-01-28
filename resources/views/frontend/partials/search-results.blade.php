{{-- resources/views/frontend/partials/search-results.blade.php --}}
<div class="space-y-4">
    @if($posts->count() > 0)
        <p class="text-sm text-gray-600">Found {{ $posts->total() }} results for "{{ request('search') }}"</p>
        <div class="divide-y">
            @foreach($posts as $post)
                @include('frontend.partials.post-card', ['post' => $post])
            @endforeach
        </div>
    @else
        <div class="text-center py-12">
            <p class="text-gray-500">No posts found matching "{{ request('search') }}"</p>
        </div>
    @endif
</div>
