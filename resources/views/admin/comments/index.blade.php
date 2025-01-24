@extends('layouts.admin')
@section('title', 'Comments')
@section('header', 'Comments')

@section('content')
    <div class="mb-4">
        <h2 class="text-2xl font-bold">Comments Management</h2>
        <div class="mt-2 flex gap-2">
            <a href="{{ route('admin.comments.index') }}" class="text-sm {{ request()->routeIs('admin.comments.index') && !request()->has('status') ? 'text-blue-600 font-semibold' : 'text-gray-600' }}">
                All ({{ $counts['total'] }})
            </a>
            <a href="{{ route('admin.comments.index', ['status' => 'pending']) }}" class="text-sm {{ request()->get('status') === 'pending' ? 'text-blue-600 font-semibold' : 'text-gray-600' }}">
                Pending ({{ $counts['pending'] }})
            </a>
            <a href="{{ route('admin.comments.index', ['status' => 'approved']) }}" class="text-sm {{ request()->get('status') === 'approved' ? 'text-blue-600 font-semibold' : 'text-gray-600' }}">
                Approved ({{ $counts['approved'] }})
            </a>
            <a href="{{ route('admin.comments.index', ['status' => 'spam']) }}" class="text-sm {{ request()->get('status') === 'spam' ? 'text-blue-600 font-semibold' : 'text-gray-600' }}">
                Spam ({{ $counts['spam'] }})
            </a>
        </div>
    </div>

    <form action="{{ route('admin.comments.batch') }}" method="POST" id="commentsForm">
        @csrf
        <div class="bg-white shadow-sm rounded-lg overflow-hidden">
            <div class="p-4 border-b">
                <div class="flex items-center gap-2">
                    <button type="submit" name="action" value="approve" class="bg-green-500 text-white px-3 py-1 rounded text-sm hover:bg-green-600">
                        Approve Selected
                    </button>
                    <button type="submit" name="action" value="mark_as_spam" class="bg-yellow-500 text-white px-3 py-1 rounded text-sm hover:bg-yellow-600">
                        Mark as Spam
                    </button>
                    <button type="submit" name="action" value="delete" class="bg-red-500 text-white px-3 py-1 rounded text-sm hover:bg-red-600">
                        Delete Selected
                    </button>
                </div>
            </div>

            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left">
                        <input type="checkbox" class="rounded border-gray-300" id="selectAll" onchange="function toggleAll(source) {
                            const checkboxes = document.getElementsByName('comment_ids[]');
                            checkboxes.forEach(checkbox => checkbox.checked = source.checked);
                        }
                        toggleAll(this)">
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Author</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Comment</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Post</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                @foreach($comments as $comment)
                    <tr>
                        <td class="px-6 py-4">
                            <input type="checkbox" name="comment_ids[]" value="{{ $comment->id }}" class="rounded border-gray-300">
                        </td>
                        <td class="px-6 py-4">
                            @if($comment->user)
                                {{ $comment->user->name }}
                            @else
                                {{ $comment->guest_name }}<br>
                                <span class="text-sm text-gray-500">{{ $comment->guest_email }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm">
                                {!! Str::limit($comment->content) !!}
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <a href="{{ route('admin.posts.show', $comment->post) }}" class="text-blue-600 hover:text-blue-900">
                                {{ Str::limit($comment->post->title, 30) }}
                            </a>
                        </td>
                        <td class="px-6 py-4">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    {{ $comment->status === 'approved' ? 'bg-green-100 text-green-800' :
                                       ($comment->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                    {{ ucfirst($comment->status) }}
                                </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ $comment->created_at->format('M d, Y H:i') }}
                        </td>
                        <td class="px-6 py-4 text-sm font-medium">
                            <a href="{{ route('admin.comments.show', $comment) }}" class="text-blue-600 hover:text-blue-900">View</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <div class="px-6 py-4">
                {{ $comments->links() }}
            </div>
        </div>
    </form>
@endsection
