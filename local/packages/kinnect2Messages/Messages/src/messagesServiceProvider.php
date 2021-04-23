<?php

namespace kinnect2Messages\Messages;

use Illuminate\Support\ServiceProvider;

class messagesServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        //Loading the route file
        require __DIR__ . '/Http/routes.php';
        // define the path for view files
        $this->loadViewsFrom(__DIR__.'/../views', 'Messages');

        // Define file which are going to be published
        $this->publishes([
            __DIR__.'/migrations/2015_11_15_000000_create_chat_table.php' =>
            base_path('database/migrations/2015_11_15_000000_create_chat_table.php')
        ]);
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('messages', function($app){
            return new Messages;
        });
    }
}