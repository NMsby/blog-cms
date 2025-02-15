<?php

namespace App\Events;

use App\Models\Comment;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewComment implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Comment $comment;

    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('post.' . $this->comment->post_id),
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->comment->id,
            'content' => $this->comment->content,
            'user' => [
                'name' => $this->comment->user->name,
                'avatar' => $this->comment->user->avatar_url,
            ],
            'created_at' => $this->comment->created_at->diffForHumans(),
            'parent_id' => $this->comment->parent_id,
        ];
    }

    public function broadcastAs(): string
    {
        return 'comment.created';
    }
}
