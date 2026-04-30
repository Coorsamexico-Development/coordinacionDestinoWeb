<?php

namespace App\Events;

use App\Models\ConfirmacionDt;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ConfirmacionDtDeleted implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $confirmacionDt;

    /**
     * Create a new event instance.
     */
    public function __construct($confirmacionDt)
    {
        $this->confirmacionDt = $confirmacionDt;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('confirmaciones'),
        ];
    }

    public function broadcastAs()
    {
        return 'deleted';
    }
}
