<?php

namespace App\Handlers\Events;

use App\ActivityNotification;
use App\Events\CreateNotification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Repository\Eloquent\NotificationRepository;

class CreateNotificationEventHandler
{
    /**
     * @var NotificationRepository
     */
    private $notificationRepository;

    /**
     * Create the event handler.
     *
     * @param NotificationRepository $notificationRepository
     */
    public function __construct(NotificationRepository $notificationRepository)
    {
        //
        $this->notificationRepository = $notificationRepository;
    }

    /**
     * Handle the event.
     *
     * @param  CreateNotification  $event
     * @return void
     */
    public function handle(CreateNotification $event)
    {
        $data = $event->get_data();

        if($data['object_type'] == \Config::get('constants_activity.OBJECT_TYPES.GROUP.NAME') && $data['type'] == \Config::get( 'constants_activity.OBJECT_TYPES.GROUP.ACTIONS.POST' )){

	        $this->notificationRepository->group_notification($data);
        }else{
            $this->notificationRepository->create_notification($data);
        }

    }
}
