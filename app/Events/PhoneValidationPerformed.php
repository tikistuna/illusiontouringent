<?php

namespace App\Events;

use App\Models\Phone;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class PhoneValidationPerformed
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $body;
    public $phone;

	/**
	 * Create a new event instance.
	 *
	 * @param $body
	 * @param Phone $phone
	 */
    public function __construct($body, Phone $phone)
    {
        $this->body = $body;
        $this->phone = $phone;
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
