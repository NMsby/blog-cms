{{-- resources/views/notifications/index.blade.php --}}
@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="mb-6 flex justify-between items-center">
            <h2 class="text-2xl font-bold">Notifications</h2>
            <div class="flex gap-2">
                <!-- Filter Controls -->
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                        Filter
                    </button>
                    <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg">
                        <a href="{{ route('notifications.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">All</a>
                        <a href="{{ route('notifications.index', ['unread' => true]) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Unread</a>
                        @foreach($stats['types'] as $type)
                            <a href="{{ route('notifications.index', ['type' => $type]) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                {{ class_basename($type) }}
                            </a>
                        @endforeach
                    </div>
                </div>

                <!-- Actions -->
                <button @click="markAllAsRead()" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700">
                    Mark All Read
                </button>
            </div>
        </div>

        <!-- Notifications List -->
        <div class="bg-white shadow overflow-hidden sm:rounded-md">
            @forelse($groupedNotifications as $date => $typeGroups)
                <div class="border-b border-gray-200">
                    <div class="px-4 py-2 bg-gray-50">
                        <h3 class="text-sm font-medium text-gray-500">{{ \Carbon\Carbon::parse($date)->format('F j, Y') }}</h3>
                    </div>

                    @foreach($typeGroups as $type => $notifications)
                        <div class="divide-y divide-gray-200">
                            @foreach($notifications as $notification)
                                <div class="p-4 {{ $notification->read_at ? 'bg-gray-50' : 'bg-white' }}">
                                    <!-- Notification Content -->
                                    @include('notifications.partials.' . Str::kebab(class_basename($notification->type)))
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            @empty
                <div class="p-4 text-center text-gray-500">
                    No notifications found
                </div>
            @endforelse
        </div>

        <div class="mt-4">
            {{ $notifications->links() }}
        </div>
    </div>

    @push('scripts')
        <script>
            function markAllAsRead() {
                if(confirm('Mark all notifications as read?')) {
                    window.location.href = '{{ route('notifications.markAllAsRead') }}';
                }
            }

            function markAsRead(id) {
                fetch(`/notifications/${id}/mark-as-read`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                }).then(response => window.location.reload());
            }
        </script>
    @endpush
@endsection
