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

class PhoneValidationFailed
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $phone;
    public $body;

	/**
	 * Create a new event instance.
	 *
	 * @param Phone $phone
	 * @param $body
	 */
    public function __construct(Phone $phone, $body)
    {
        $this->phone = $phone;
        $this->body = $body;
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
