<?php

namespace SevenSpan\Chat\Events;

use SevenSpan\Chat\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class MessageDelete implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $channelSlug;
    public $message;

    public function __construct($channelSlug, Message $message)
    {
        $this->message = $message;
        $this->channelSlug = $channelSlug;
    }

    public function broadcastOn()
    {
        return new Channel($this->channelSlug);
    }

    // Event name
    public function broadcastAs()
    {
        return 'message-delete';
    }
}
