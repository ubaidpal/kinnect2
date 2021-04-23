<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ActivityDelete extends Event
{
    use SerializesModels;
    private $options;

    /**
     * Create a new event instance.
     *
     *      */
    public function __construct(array $options)
    {

        return $this->options = $options;
    }

    public function get_options(  ) {
        return $this->options;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}
