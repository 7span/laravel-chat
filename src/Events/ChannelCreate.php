<?php

namespace SevenSpan\Chat\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use SevenSpan\Chat\Channel;

class ChannelCreate implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $channel;

    public function __construct(Channel $channel)
    {
        $this->channel = $channel;
    }

    public function broadcastOn()
    {
        return new Channel($this->channel);
    }

    // Event name
    public function broadcastAs()
    {
        return 'channel-create';
    }
}
