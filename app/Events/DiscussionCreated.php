<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DiscussionCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $discussion;
    public $html;

    /**
     * Create a new event instance.
     */
    public function __construct($discussion, $html)
    {
        $this->discussion = $discussion;
        $this->html = $html; // html đã được render sẵn từ controller để frontend chỉ việc append
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        // Sử dụng PresenceChannel để quản lý những người đang trong phòng học
        return [
            new PresenceChannel('lecture.' . $this->discussion->lecture_id),
        ];
    }

    /**
     * Tên sự kiện gửi về frontend.
     */
    public function broadcastAs(): string
    {
        return 'discussion.created';
    }
}
