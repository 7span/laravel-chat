<?php

namespace SevenSpan\Chat\Events;

use SevenSpan\Chat\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class UpdateMessage implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        private $channelSlug,
        private Message $message
    ) {
    }

    public function broadcastOn()
    {
        return new Channel($this->channelSlug);
    }

    public function broadcastWith()
    {
        return $this->message->toArray();
    }

    public function broadcastAs()
    {
        return 'update-message';
    }
}
