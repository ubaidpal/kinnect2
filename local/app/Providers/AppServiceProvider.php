<?php namespace App\Providers;

use App\Event;
use App\Events\SendEmail;
use App\UserMembership;
use Illuminate\Support\ServiceProvider;


class AppServiceProvider extends ServiceProvider {

	/**
	 * Bootstrap any application services.
	 *
	 * @return void
	 */
	public function boot()
	{

		/*\Event::listen('event.created', function(\Event $post)
		{
			dd($post);
		});*/
		/*\DB::listen(function($query, $params, $time)
		{
			//dd(array($query, $params, $time));
		});*/
		Event::created(function ($item) {
			//dd($item);
		});
	}

	/**
	 * Register any application services.
	 *
	 * This service provider is a great spot to register your various container
	 * bindings with the application. As you can see, we are registering our
	 * "Registrar" implementation here. You can add your own bindings too!
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app->bind(
			'Illuminate\Contracts\Auth\Registrar',
			'App\Services\Registrar'
		);
	}

}
