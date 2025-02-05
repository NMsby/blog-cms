{{-- resources/views/components/notification-bell.blade.php --}}
<div class="relative"
     x-data="{ open: false }"
     @click.away="open = false">
    <button @click="open = !open"
            class="relative p-2 text-gray-600 hover:text-gray-800 rounded-full hover:bg-gray-100">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
        </svg>
        @if($unreadCount > 0)
            <span class="absolute top-0 right-0 block h-2 w-2 transform translate-x-1/2 -translate-y-1/2 rounded-full bg-red-500"></span>
        @endif
    </button>

    <div x-show="open"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="transform opacity-0 scale-95"
         x-transition:enter-end="transform opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="transform opacity-100 scale-100"
         x-transition:leave-end="transform opacity-0 scale-95"
         class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-xl py-2 z-50">

        <div class="px-4 py-2 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-sm font-semibold text-gray-700">Notifications</h3>
            @if($unreadCount > 0)
                <a href="#" class="text-xs text-blue-600 hover:text-blue-800">Mark all as read</a>
            @endif
        </div>

        <div class="max-h-64 overflow-y-auto">
            @forelse(auth()->user()->notifications()->take(5)->get() as $notification)
                <a href="{{ route('notifications.show', $notification) }}"
                   class="block px-4 py-3 hover:bg-gray-50 transition {{ $notification->read_at ? 'opacity-75' : '' }}">
                    <p class="text-sm text-gray-600">
                        {!! $notification->data['message'] !!}
                    </p>
                    <p class="text-xs text-gray-500 mt-1">
                        {{ $notification->created_at->diffForHumans() }}
                    </p>
                </a>
            @empty
                <div class="px-4 py-3 text-sm text-gray-500">
                    No notifications
                </div>
            @endforelse
        </div>

        <div class="px-4 py-2 border-t border-gray-200">
            <a href="{{ route('notifications.index') }}"
               class="block text-sm text-center text-blue-600 hover:text-blue-800">
                View all notifications
            </a>
        </div>
    </div>
</div>
