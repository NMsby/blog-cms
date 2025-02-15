@auth
    <form action="{{ route('blog.comments.store', $post) }}" method="POST" class="bg-white rounded-lg p-6 shadow">
        @csrf

        {{-- Status Message --}}
        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-md">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-md">
                {{ session('error') }}
            </div>
        @endif

        <div class="mb-4">
            <label for="content" class="block text-sm font-medium text-gray-700 mb-2">Your Comment</label>
            <textarea name="content" id="content" rows="4" required
                      class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-1"></textarea>
        </div>
        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
            Post Comment
        </button>
    </form>
@else
    <div class="bg-white rounded-lg p-6 shadow text-center">
        <p class="text-gray-600 mb-4">To comment on this post, please register.</p>
        <div class="flex justify-center gap-4">
            <a href="{{ route('login') }}" class="inline-block px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">
                Login
            </a>
            <a href="{{ route('register') }}" class="inline-block px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                Register
            </a>
        </div>
    </div>
@endauth
