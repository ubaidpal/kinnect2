<?php

namespace App\Providers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;

class BattlesServiceProvider extends ServiceProvider
{

    public function boot()
    {
    }


    public function register()
    {
        App::bind('BattlesClassInterface', function()
        {
            return new \App\Kinnect2Classes\BattlesClass;
        });
    }
}