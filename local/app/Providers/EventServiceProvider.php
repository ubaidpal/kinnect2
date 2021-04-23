<?php namespace App\Providers;

use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider {

	/**
	 * The event handler mappings for the application.
	 *
	 * @var array
	 */
	protected $listen = [
		'event.name' => [
			'EventListener',
		],
		'App\Events\ActivityLog' => ['App\Handlers\Events\ActivityLogEventHandler'],
		'App\Events\SendEmail' => ['App\Handlers\Events\SendEmailHandler'],
		\App\Events\GetNotification::class => [\App\Handlers\Events\GetNotificationEventHandler::class],
		\App\Events\CreateNotification::class => [\App\Handlers\Events\CreateNotificationEventHandler::class],
		\App\Events\ActivityDelete::class => [\App\Handlers\Events\ActivityDeleteHandler::class],
		\App\Events\NotificationDelete::class => [\App\Handlers\Events\NotificationDeleteHandler::class],
		'auth.login' => [
			\App\Handlers\Events\AuthLoginEventHandler::class
		],
		'auth.logout' => [
				\App\Handlers\Events\AuthLogoutEventHandler::class
		]
	];

	/**
	 * Register any other events for your application.
	 *
	 * @param  \Illuminate\Contracts\Events\Dispatcher  $events
	 * @return void
	 */
	public function boot(DispatcherContract $events)
	{
		parent::boot($events);
	}

}
