<?php namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Intervention\Image\Exception\NotFoundException;
use League\OAuth2\Server\Exception\AccessDeniedException;
use PhpSpec\Exception\Example\ErrorException;
use Symfony\Component\Debug\Exception\FatalErrorException;

class Handler extends ExceptionHandler {

	/**
	 * A list of the exception types that should not be reported.
	 *
	 * @var array
	 */
	protected $dontReport = [
		'Symfony\Component\HttpKernel\Exception\HttpException'
	];

	/**
	 * Report or log an exception.
	 *
	 * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
	 *
	 * @param  \Exception  $e
	 * @return void
	 */
	public function report(Exception $e)
	{
		return parent::report($e);
	}

	/**
	 * Render an exception into an HTTP response.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Exception  $e
	 * @return \Illuminate\Http\Response
	 */
	public function render($request, Exception $e)
	{
		if ($e instanceof \Bican\Roles\Exceptions\RoleDeniedException) {
			// you can for example flash message, redirect...
			return redirect()->back();
		}
		/*if(\URLFilter::filter()){
			if($e instanceof AccessDeniedException){
				return \Api::invalid_access_token();
			}
		}*/
		return parent::render($request, $e);
	}

}
