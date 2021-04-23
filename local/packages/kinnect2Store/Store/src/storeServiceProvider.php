<?php

namespace kinnect2Store\Store;

use Illuminate\Support\ServiceProvider;

class storeServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot(\Illuminate\Routing\Router $router)
    {

        //Loading the route file
        require __DIR__ . '/Http/routes/routes.php';
        // define the path for view files
        $this->loadViewsFrom(__DIR__.'/../views', 'Store');

        // Define file which are going to be published
        $this->publishes([
            __DIR__.'/migrations/2015_11_15_000000_create_store_products_table.php' =>
            base_path('database/migrations/2015_11_15_000000_create_store_products_table.php'),
            //Store related configuration
            __DIR__.'/config/BrandStore.php' => config_path('BrandStore.php'),
             //Store related constatns
            __DIR__.'/config/constants_brandstore.php' => config_path('constants_brandstore.php'),
        ]);
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->app['router'];
        $this->app['router']->middleware('store_auth', '\kinnect2Store\Store\Http\Middleware\Store_Auth');

        $this->app->bind('store', function($app){

            return new Store;

        });
    }
}
