<?php

namespace SevenSpan\Chat\Events;

use SevenSpan\Chat\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class SendMessage implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public $channelSlug,
        public Message $message
    ) {
    }

    public function broadcastOn()
    {
        return new Channel($this->channelSlug);
    }

    public function broadcastAs()
    {
        return 'send-message';
    }
}
