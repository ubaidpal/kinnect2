<?php

namespace App\Facades;
use Illuminate\Support\Facades\Facade;

class PollsClassFacade extends Facade{

    protected static function getFacadeAccessor() { return 'PollsClassInterface'; }

}
