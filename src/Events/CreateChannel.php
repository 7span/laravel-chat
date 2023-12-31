<?php

namespace SevenSpan\Chat\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use SevenSpan\Chat\Models\Channel as ChannelModal;

class CreateChannel implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        private ChannelModal $channel
    ) {
    }

    public function broadcastOn()
    {
        return new Channel($this->channel->slug);
    }

    public function broadcastWith()
    {
        return $this->channel->toArray();
    }

    public function broadcastAs()
    {
        return 'create-channel';
    }
}
