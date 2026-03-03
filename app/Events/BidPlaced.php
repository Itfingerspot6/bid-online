<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BidPlaced implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $bid;

    /**
     * Create a new event instance.
     */
    public function __construct(\App\Models\Bid $bid)
    {
        $this->bid = $bid->load('user');
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('auctions.' . $this->bid->auction_id),
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'amount' => $this->bid->amount,
            'user_name' => $this->bid->user->name,
            'current_price' => $this->bid->auction->current_price,
            'created_at' => $this->bid->created_at->diffForHumans(),
        ];
    }
}
