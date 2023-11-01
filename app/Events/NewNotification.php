<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\ConfirmacionDt;


class NewNotification implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    //protected
    public $confirmacionDt;
    /**
     * Create a new event instance.
     */
    public function __construct(ConfirmacionDt $confirmacionDt)
    {
        //
        //$this-> confirmacionDt = $confirmacionDt->id;

    }

    public function broadcastWith(): array
    {
        return ['id' => $this->confirmacionDt->id];
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('confirmacion'),
        ];
    }

    public function broadcastAs()
    {
        return 'notification';
    }
}
