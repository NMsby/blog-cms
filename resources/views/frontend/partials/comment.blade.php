<div class="border-b last:border-b-0 pb-6">
    <div class="flex items-start gap-4">
        @if($comment->user && $comment->user->avatar)
            <img src="{{ asset('storage/' . $comment->user->avatar) }}" alt="" class="w-10 h-10 rounded-full">
        @else
            <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center">
                <span class="text-gray-500">{!! substr($comment->user ? $comment->user->name : $comment->guest_name, 0, 1) !!}</span>
            </div>
        @endif
        <div class="flex-1">
            <div class="mb-2">
                <span class="font-semibold">{{ $comment->user ? $comment->user->name : $comment->guest_name }}</span>
                <span class="text-sm text-gray-500 ml-2">{{ $comment->created_at->diffForHumans() }}</span>
            </div>

            @if($comment->status === 'pending')
                <span class="ml-2 px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-800 rounded-full">
                    Pending Approval
                </span>
            @elseif($comment->status === 'approved')
                <span class="ml-2 px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">
                    Approved
                </span>
            @endif

            <div class="text-gray-700">{{ $comment->content }}</div>

            @auth
                <div class="flex items-center text-sm space-x-4 mt-2">
                    @if($comment->canEdit(auth()->user()))
                        <a href="{{ route('blog.comments.edit', $comment) }}"
                           class="text-blue-600 hover:text-blue-800">
                            Edit
                        </a>
                        <form action="{{ route('blog.comments.destroy', $comment) }}"
                              method="POST"
                              class="inline-block"
                              onsubmit="return confirm('Are you sure you want to delete this comment?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800">
                                Delete
                            </button>
                        </form>
                        <span class="text-gray-300">|</span>
                    @endif

                    <button onclick="function showReplyForm(commentId) {
                        const form = document.getElementById('reply-form-' + commentId);
                        form.classList.toggle('hidden');
                    }
                    showReplyForm('{{ $comment->id }}')"
                            class="text-blue-600 hover:text-blue-800">
                        Reply
                    </button>
                </div>

                <form id="reply-form-{{ $comment->id }}" action="{{ route('blog.comments.store', $post) }}" method="POST" class="mt-4 hidden">
                    @csrf
                    <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                    <textarea name="content" rows="2" required class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" placeholder="Write a reply..."></textarea>
                    <div class="mt-2">
                        <x-primary-button>Post Reply</x-primary-button>
                    </div>
                </form>
            @endauth

            @if($comment->replies->count() > 0)
                <div class="mt-4 ml-4 space-y-4">
                    @foreach($comment->replies as $reply)
                        @include('frontend.partials.comment', ['comment' => $reply])
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
