<?php

namespace App\Providers;

use App\Classes\UrlFilter;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;

class URLFilterServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        App::bind('UrlFilter', function(){
           return new UrlFilter();
        });
    }
}
