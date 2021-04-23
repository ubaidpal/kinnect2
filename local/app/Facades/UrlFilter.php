<?php
/**
 * Created by   :  Muhammad Yasir
 * Project Name : local
 * Product Name : PhpStorm
 * Date         : 06-11-15 2:44 PM
 * File Name    : UrlFilter.php
 */

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class UrlFilter extends Facade {
//	protected static function filter (  )
//	{
//		return 'UrlFilter';
//	}
	protected static function getFacadeAccessor() { return 'UrlFilter'; }
}
