<?php

namespace App\Handlers\Events;

use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class AuthLoginEventHandler
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
     * @param  Events  $event
     * @return void
     */
    public function handle(User $user)
    {
        $user->lastlogin_date = Carbon::now();
        $user->lastlogin_ip = \Request::getClientIp();
        if(!\Schema::hasColumn('users', 'login_counter'))
        {
            \Schema::table('users', function ($table) {
                $table->integer('login_counter', FALSE, TRUE);
            });
        }
        $user->increment('login_counter');
        $user->save();
    }
}
