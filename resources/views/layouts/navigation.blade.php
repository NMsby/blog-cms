<nav x-data="{ open: false, searchOpen: false }" class="bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ auth()->check() ? route('admin.admin.dashboard') : route('home') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800"/>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    @auth
                        @switch(auth()->user()->role)
                            @case('admin')
                                <x-nav-link :href="route('admin.admin.dashboard')"
                                            :active="request()->routeIs('admin.dashboard')">
                                    {{ __('Admin Dashboard') }}
                                </x-nav-link>
                                <x-nav-link :href="route('admin.posts.index')"
                                            :active="request()->routeIs('admin.posts.*')">
                                    {{ __('Posts') }}
                                </x-nav-link>
                                <x-nav-link :href="route('admin.comments.index')"
                                            :active="request()->routeIs('admin.comments.*')">
                                    {{ __('Comments') }}
                                </x-nav-link>
                                <x-nav-link :href="route('admin.users.index')"
                                            :active="request()->routeIs('admin.users.*')">
                                    {{ __('Users') }}
                                </x-nav-link>
                                <x-nav-link :href="route('admin.settings.index')"
                                            :active="request()->routeIs('admin.settings.*')">
                                    {{ __('Settings') }}
                                </x-nav-link>
                                @break

                            @case('editor')
                                <x-nav-link :href="route('editor.dashboard')"
                                            :active="request()->routeIs('editor.dashboard')">
                                    {{ __('Editor Dashboard') }}
                                </x-nav-link>
                                <x-nav-link :href="route('admin.posts.index')"
                                            :active="request()->routeIs('admin.posts.*')">
                                    {{ __('Manage Posts') }}
                                </x-nav-link>
                                <x-nav-link :href="route('admin.comments.index')"
                                            :active="request()->routeIs('admin.comments.*')">
                                    {{ __('Comments') }}
                                </x-nav-link>
                                <x-nav-link :href="route('blog.index')" :active="request()->routeIs('blog.*')">
                                    {{ __('Blog') }}
                                </x-nav-link>
                                @break

                            @case('author')
                                <x-nav-link :href="route('authorauthor.dashboard')"
                                            :active="request()->routeIs('author.dashboard')">
                                    {{ __('Author Dashboard') }}
                                </x-nav-link>
                                <x-nav-link :href="route('authorposts.create')"
                                            :active="request()->routeIs('author.posts.create')">
                                    {{ __('Create Post') }}
                                </x-nav-link>
                                <x-nav-link :href="route('authorposts.index')"
                                            :active="request()->routeIs('author.posts.*')">
                                    {{ __('My Posts') }}
                                </x-nav-link>
                                <x-nav-link :href="route('blog.index')" :active="request()->routeIs('blog.*')">
                                    {{ __('Blogs') }}
                                </x-nav-link>
                                @break

                            @default
                                <x-nav-link :href="route('home')" :active="request()->routeIs('home')">
                                    {{ __('Home') }}
                                </x-nav-link>
                                <x-nav-link :href="route('blog.index')" :active="request()->routeIs('blog.*')">
                                    {{ __('Blog') }}
                                </x-nav-link>
                        @endswitch
                    @else
                        <x-nav-link :href="route('home')" :active="request()->routeIs('home')">
                            {{ __('Home') }}
                        </x-nav-link>
                        <x-nav-link :href="route('blog.index')" :active="request()->routeIs('blog.*')">
                            {{ __('Blog') }}
                        </x-nav-link>
                    @endauth
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <!-- Search -->
                <button @click="searchOpen = true" class="p-2 text-gray-500 hover:text-gray-700 mr-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </button>

                <!-- User Menu -->
                @auth
                    <!-- Notifications -->
                    <div class="relative mr-3" x-data="{ open: false }">
                        <button @click="open = !open" class="relative p-2 text-gray-600 hover:text-gray-800">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                            </svg>
                            @if(auth()->user()->unreadNotifications->count() > 0)
                                <span class="absolute top-0 right-0 block h-2 w-2 rounded-full bg-red-500"></span>
                            @endif
                        </button>

                        <div x-show="open"
                             @click.away="open = false"
                             class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg py-2">
                            <div class="px-4 py-2 border-b border-gray-200">
                                <h3 class="text-sm font-semibold text-gray-700">Notifications</h3>
                            </div>

                            <div class="max-h-64 overflow-y-auto">
                                @forelse(auth()->user()->notifications()->take(5)->get() as $notification)
                                    <div class="px-4 py-2 hover:bg-gray-50 {{ $notification->read_at ? 'opacity-50' : '' }}">
                                        <p class="text-sm text-gray-600">
                                            @if($notification->type === 'App\Notifications\NewCommentNotification')
                                                New comment on your post "{{ $notification->data['post_title'] }}"
                                            @elseif($notification->type === 'App\Notifications\CommentReplyNotification')
                                                New reply to your comment
                                            @endif
                                        </p>
                                        <p class="text-xs text-gray-500 mt-1">
                                            {{ $notification->created_at->diffForHumans() }}
                                        </p>
                                    </div>
                                @empty
                                    <div class="px-4 py-2 text-sm text-gray-500">
                                        No notifications
                                    </div>
                                @endforelse
                            </div>

                            @if(auth()->user()->notifications->count() > 5)
                                <div class="px-4 py-2 border-t border-gray-200">
                                    <a href="{{ route('notifications.index') }}" class="text-sm text-blue-600 hover:text-blue-800">
                                        View all notifications
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button
                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 hover:text-gray-700 focus:outline-none transition">
                                @if(Auth::user()->avatar)
                                    <img src="{{ asset('storage/' . Auth::user()->avatar) }}"
                                         class="w-8 h-8 rounded-full mr-2" alt="">
                                @endif
                                <div>{{ Auth::user()->name }}</div>
                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                         viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                              d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                              clip-rule="evenodd"/>
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            @php
                                $role = auth()->user()->role;
                                $profileRoute = match($role) {
                                    'admin' => route('admin.profile'),
                                    'editor' => route('editor.profile.edit'),
                                    'author' => route('authorprofile.edit'),
                                    default => route('profile.edit'),
                                }
                            @endphp

                            <x-dropdown-link :href="$profileRoute">
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            @if($role === 'author')
                                <x-dropdown-link>
                                    {{ __('Statistics') }}
                                </x-dropdown-link>

                                <x-dropdown-link :href="route('notifications.preferences')">
                                    {{ __('Notification Settings') }}
                                </x-dropdown-link>
                            @endif

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                                 onclick="event.preventDefault(); this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @else
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('login') }}" class="inline-flex items-center px-4 py-2 border border-gray-300
                        rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none
                        focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Login
                        </a>
                        <a href="{{ route('register') }}"
                           class="inline-flex items-center px-4 py-2 border border-transparent rounded-md text-sm
                           font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2
                           focus:ring-offset-2 focus:ring-blue-500">
                            Register
                        </a>
                    </div>
                @endauth
            </div>

            <!-- Mobile menu button -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = !open"
                        class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': !open }" class="inline-flex"
                              stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16"/>
                        <path :class="{'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                              stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile menu -->
    <div :class="{'block': open, 'hidden': !open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            @auth
                @switch(auth()->user()->role)
                    @case('admin')
                        <x-responsive-nav-link :href="route('admin.admin.dashboard')"
                                               :active="request()->routeIs('admin.dashboard')">
                            {{ __('Dashboard') }}
                        </x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('admin.posts.index')"
                                               :active="request()->routeIs('admin.posts.*')">
                            {{ __('Posts') }}
                        </x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('admin.comments.index')"
                                               :active="request()->routeIs('admin.comments.*')">
                            {{ __('Comments') }}
                        </x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('admin.users.index')"
                                               :active="request()->routeIs('admin.users.*')">
                            {{ __('Users') }}
                        </x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('admin.settings.index')"
                                               :active="request()->routeIs('admin.settings.*')">
                            {{ __('Settings') }}
                        </x-responsive-nav-link>
                        @break

                    @case('editor')
                        <x-responsive-nav-link :href="route('editor.dashboard')"
                                               :active="request()->routeIs('editor.dashboard')">
                            {{ __('Dashboard') }}
                        </x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('author.posts.index')"
                                               :active="request()->routeIs('admin.posts.*')">
                            {{ __('Posts') }}
                        </x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('admin.comments.index')"
                                               :active="request()->routeIs('admin.comments.*')">
                            {{ __('Comments') }}
                        </x-responsive-nav-link>
                        @break

                    @case('author')
                        <x-responsive-nav-link :href="route('authorauthor.dashboard')"
                                               :active="request()->routeIs('author.dashboard')">
                            {{ __('Dashboard') }}
                        </x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('admin.posts.create')"
                                               :active="request()->routeIs('admin.posts.create')">
                            {{ __('Create Post') }}
                        </x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('admin.posts.index')"
                                               :active="request()->routeIs('admin.posts.*')">
                            {{ __('My Posts') }}
                        </x-responsive-nav-link>
                        @break

                    @default
                        <x-responsive-nav-link :href="route('home')" :active="request()->routeIs('home')">
                            {{ __('Home') }}
                        </x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('blog.index')" :active="request()->routeIs('blog.*')">
                            {{ __('Blog') }}
                        </x-responsive-nav-link>
                @endswitch
            @else
                <x-responsive-nav-link :href="route('home')" :active="request()->routeIs('home')">
                    {{ __('Home') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('blog.index')" :active="request()->routeIs('blog.*')">
                    {{ __('Blog') }}
                </x-responsive-nav-link>
            @endauth
        </div>
    </div>

    <!-- Search Modal -->
    <div x-data="{ query: '', suggestions: [] }"
         x-show="searchOpen"
         x-transition
         class="fixed inset-0 z-50 overflow-y-auto"
         style="display: none;">
        <div class="min-h-screen px-4 text-center">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                 @click="searchOpen = false"></div>

            <div class="inline-block w-full max-w-2xl my-8 p-6 text-left align-middle bg-white shadow-xl rounded-lg">
                <form action="{{ route('blog.index') }}" method="GET">
                    <input type="text"
                           name="search"
                           x-model="query"
                           @input.debounce.300ms="fetch('blog/search/suggestions?q=' + query)
                            .then(res => res.json())
                            .then(data => suggestions = data)"
                           placeholder="Search posts..."
                           class="w-full text-xl border-0 focus:ring-0"
                           @keydown.escape.window="searchOpen = false"
                           autofocus>
                </form>

                <div x-show="suggestions.length" class="mt-4 divide-y">
                    <template x-for="post in suggestions" :key="post.id">
                        <a :href="`/blog/${post.slug}`"
                           class="block py-2 px-3 hover:bg-gray-100 rounded transition">
                            <div x-text="post.title" class="font-medium"></div>
                        </a>
                    </template>
                </div>
            </div>
        </div>
    </div>
</nav>
