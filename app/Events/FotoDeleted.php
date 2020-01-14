<?php

namespace Inmuebles\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Inmuebles\Models\Propiedades\Foto;

class FotoDeleted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $foto;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Foto $foto)
    {
        $this->foto = $foto;
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
