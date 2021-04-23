<?php

namespace App\Handlers\Events;

use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class AuthLogoutEventHandler
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
    public function handle(User $user = NULL)
    {
        \Session::flush();
        if(!isset($user->id)){
            return redirect('/login');
        }
    }
}
