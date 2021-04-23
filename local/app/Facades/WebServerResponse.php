<?php
/**
 * Created by   :  Muhammad Yasir
 * Project Name : local
 * Product Name : PhpStorm
 * Date         : 07-12-15 8:04 PM
 * File Name    : WebServerResponse.php
 */

namespace App\Facades;
use Illuminate\Support\Facades\Facade;

class WebServerResponse extends Facade {
	protected static function getFacadeAccessor() { return 'WebServerResponse'; }
}
