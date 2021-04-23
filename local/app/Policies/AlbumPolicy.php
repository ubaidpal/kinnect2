<?php

namespace App\Policies;

use App\Album;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AlbumPolicy
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

	public function update(User $user, Album $album  ) {
		return $user->id === $album->owner_id;
	}
	public function delete(User $user, Album $album  ) {
		return $user->id === $album->owner_id;
	}
}
