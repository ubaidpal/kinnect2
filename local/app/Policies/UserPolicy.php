<?php

namespace App\Policies;

use App\Consumer;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
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

	public function brand(User $user, User $current) {

		return $current->user_type == \Config::get('constants.BRAND_USER') ;
	}
	public function consumer(User $user,  User $current   ) {
		return $current->user_type == \Config::get('constants.REGULAR_USER') ;
	}

	public function unfriend(User $user, User $current)
	{
		return $user->id == $current->resource_id;
	}
}
