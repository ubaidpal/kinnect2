<?php

namespace App\Policies;

use App\Group;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class GroupPolicy
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

    public function update(User $user, Group $group)
    {
        return $user->id === $group->creator_id;
    }
    public function delete(User $user, Group $group)
    {
        return $user->id === $group->creator_id;
    }
}
