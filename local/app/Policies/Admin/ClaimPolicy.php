<?php

namespace App\Policies\Admin;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use kinnect2Store\Store\StoreClaim;

class ClaimPolicy
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

    public function resolve(User $user, StoreClaim $claim)
    {
        return $user->id === $claim->arbitrator_id ;//|| $user->is('super.admin');
    }

    public function arbitrator(User $user, StoreClaim $claim)
    {
       // return (($claim->status != \Config::get('admin_constants.CLAIM_STATUS.RESOLVED')) && ($user->id === $claim->arbitrator_id || $user->is('super.admin')));
        return ($claim->status != \Config::get('admin_constants.CLAIM_STATUS.RESOLVED') && $user->id === $claim->arbitrator_id );
    }
}
