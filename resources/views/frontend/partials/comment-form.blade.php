{{-- resources/views/frontend/partials/comment-form.blade.php --}}
<form action="{{ route('comments.store', $post) }}" method="POST" class="bg-white rounded-lg p-6 shadow">
    @csrf
    <div class="mb-4">
        <label for="content" class="block text-sm font-medium text-gray-700 mb-2">Your Comment</label>
        <textarea name="content" id="content" rows="4" required
                  class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-1"></textarea>
    </div>

    @guest
        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label for="guest_name" class="block text-sm font-medium text-gray-700 mb-2">Name</label>
                <input type="text" name="guest_name" id="guest_name" required
                       class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-1">
            </div>
            <div>
                <label for="guest_email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                <input type="email" name="guest_email" id="guest_email" required
                       class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-1">
            </div>
        </div>
    @endguest

    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
        Post Comment
    </button>
</form>
