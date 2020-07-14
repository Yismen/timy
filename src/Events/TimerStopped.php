<?php

namespace Dainsys\Timy\Events;

use Dainsys\Timy\Resources\TimerResource;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TimerStopped implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;

    public $timer;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($user, $timer)
    {
        $this->user = $user;
        $this->timer = TimerResource::make($timer);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('Timy.User.' . $this->user->id);
    }
}
