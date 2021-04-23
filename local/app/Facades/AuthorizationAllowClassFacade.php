<?php

namespace App\Facades;
use Illuminate\Support\Facades\Facade;

class AuthorizationAllowClassFacade extends Facade{

    protected static function getFacadeAccessor() { return 'AuthorizationAllowClassInterface'; }

}