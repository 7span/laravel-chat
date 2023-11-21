<?php

namespace SevenSpan\Chat\Events;

use SevenSpan\Chat\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class DeleteMessage implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        private $channelSlug,
        public Message $message
    ) {
    }

    public function broadcastOn()
    {
        return new Channel($this->channelSlug);
    }

    // Event name
    public function broadcastAs()
    {
        return 'delete-message';
    }
}
