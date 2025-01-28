<aside class="w-64 bg-gray-800 text-white min-h-screen">
    <div class="p-6">
        <nav>
            <a href="{{ route('admin.admin.dashboard') }}" class="block py-2.5 px-4 rounded transition duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-gray-900' : 'hover:bg-gray-700' }}">
                Dashboard
            </a>

            <a href="{{ route('admin.posts.index') }}" class="block py-2.5 px-4 rounded transition duration-200 {{ request()->routeIs('admin.posts.*') ? 'bg-gray-900' : 'hover:bg-gray-700' }}">
                Posts
            </a>

            <a href="{{ route('admin.categories.index') }}" class="block py-2.5 px-4 rounded transition duration-200 {{ request()->routeIs('admin.categories.*') ? 'bg-gray-900' : 'hover:bg-gray-700' }}">
                Categories
            </a>

            <a href="{{ route('admin.tags.index') }}" class="block py-2.5 px-4 rounded transition duration-200 {{ request()->routeIs('admin.tags.*') ? 'bg-gray-900' : 'hover:bg-gray-700' }}">
                Tags
            </a>

            <a href="{{ route('admin.comments.index') }}" class="block py-2.5 px-4 rounded transition duration-200 {{ request()->routeIs('admin.comments.*') ? 'bg-gray-900' : 'hover:bg-gray-700' }}">
                Comments
            </a>

            <a href="{{ route('admin.media.index') }}" class="block py-2.5 px-4 rounded transition duration-200 {{ request()->routeIs('admin.media.*') ? 'bg-gray-900' : 'hover:bg-gray-700' }}">
                Media
            </a>

            @if(auth()->user()->isAdmin())
                <a href="{{ route('admin.users.index') }}" class="block py-2.5 px-4 rounded transition duration-200 {{ request()->routeIs('admin.users.*') ? 'bg-gray-900' : 'hover:bg-gray-700' }}">
                    Users
                </a>

                <a href="{{ route('admin.settings.index') }}" class="block py-2.5 px-4 rounded transition duration-200 {{ request()->routeIs('admin.settings.*') ? 'bg-gray-900' : 'hover:bg-gray-700' }}">
                    Settings
                </a>
            @endif
        </nav>
    </div>
</aside>
