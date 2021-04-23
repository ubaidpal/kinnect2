<?php

namespace App\Providers;

use App\Album;
use App\Event;
use App\Group;
use App\Policies\Admin\ClaimPolicy;
use App\Policies\AlbumPolicy;
use App\Policies\EventPolicy;
use App\Policies\UserPolicy;
use App\User;
use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Policies\GroupPolicy;
use kinnect2Store\Store\StoreClaim;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
        Album::class => AlbumPolicy::class,
        Event::class => EventPolicy::class,
        User::class => UserPolicy::class,
        Group::class => GroupPolicy::class,
        StoreClaim::class => ClaimPolicy::class,
    ];

    /**
     * Register any application authentication / authorization services.
     *
     * @param  \Illuminate\Contracts\Auth\Access\Gate  $gate
     * @return void
     */
    public function boot(GateContract $gate)
    {
        parent::registerPolicies($gate);

        //
    }
}
