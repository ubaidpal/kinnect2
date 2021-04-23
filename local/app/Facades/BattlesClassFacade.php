<?php

namespace App\Facades;
use Illuminate\Support\Facades\Facade;

class BattlesClassFacade extends Facade{

    protected static function getFacadeAccessor() { return 'BattlesClassInterface'; }

}