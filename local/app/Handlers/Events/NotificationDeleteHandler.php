<?php

namespace App\Handlers\Events;

use App\ActivityNotification;
use App\Events\NotificationDelete;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotificationDeleteHandler
{
    /**
     * Create the event handler.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  NotificationDelete $event
     * @return void
     */
    public function handle(NotificationDelete $event)
    {
        $data = $event->get_data();

        ActivityNotification::whereResourceId($data['resource_id'])
            ->whereSubjectId($data['subject_id'])
            ->whereRead(0)
            ->whereType($data['type'])
            ->delete();
    }
}
