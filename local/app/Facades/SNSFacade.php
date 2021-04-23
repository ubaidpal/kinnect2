<?php

namespace App\Facades;
use Illuminate\Support\Facades\Facade;

class SNSFacade extends Facade {
	protected static function getFacadeAccessor() {
		return 'SNS';
	}
}
