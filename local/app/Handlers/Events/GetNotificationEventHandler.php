<?php

namespace App\Handlers\Events;


use App\ActivityNotification;
use App\Events\GetNotification;
use App\Repository\Eloquent\NotificationRepository;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class GetNotificationEventHandler
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
	    $this->notificationRepository = $notificationRepository;
    }

    /**
     * Handle the event.
     *
     * @param  GetNotification  $event
     */
    public function handle(GetNotification $event)
    {
	    $data = $event->get_data();
	    return $this->notificationRepository->get_notifications($data);
    }
}
