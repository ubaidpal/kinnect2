<?php

namespace App\Policies;

use App\Event;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class EventPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

	public function add_photo(User $user, Event $event   ) {
		return $user->id === $event->user_id ;
	}

    public function update(User $user, Event $event   ) {
        return $user->id === $event->user_id ;
    }
}
