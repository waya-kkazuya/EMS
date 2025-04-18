<?php

namespace App\Events;

use App\Models\ItemRequest;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RequestedItemDetectEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $itemRequest;

    /**
     * Create a new event instance.
     */
    public function __construct(ItemRequest $itemRequest)
    {
        $this->itemRequest = $itemRequest;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}
