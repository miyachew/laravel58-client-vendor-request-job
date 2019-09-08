<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use App\ClientRequest;

class RequestSentEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var ClientRequest
     */
    public $clientRequest;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(ClientRequest $clientRequest)
    {
        $this->clientRequest = $clientRequest;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
