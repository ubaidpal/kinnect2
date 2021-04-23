<?php

/*
 * This file is part of OAuth 2.0 Laravel.
 *
 * (c) Luca Degasperi <packages@lucadegasperi.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Http\Middleware;

use Closure;
use League\OAuth2\Server\Exception\InvalidScopeException;
use LucaDegasperi\OAuth2Server\Authorizer;


/**
 * This is the oauth middleware class.
 *
 * @author Luca Degasperi <packages@lucadegasperi.com>
 */
class OAuthMiddleware
{
	/**
	 * The Authorizer instance.
	 *
	 * @var \LucaDegasperi\OAuth2Server\Authorizer
	 */
	protected $authorizer;

	/**
	 * Whether or not to check the http headers only for an access token.
	 *
	 * @var bool
	 */
	protected $httpHeadersOnly = false;

	/**
	 * Create a new oauth middleware instance.
	 *
	 * @param \LucaDegasperi\OAuth2Server\Authorizer $authorizer
	 * @param bool $httpHeadersOnly
	 */
	public function __construct(Authorizer $authorizer, $httpHeadersOnly = false)
	{
		$this->authorizer = $authorizer;
		$this->httpHeadersOnly = $httpHeadersOnly;
	}

	/**
	 * Handle an incoming request.
	 *
	 * @param \Illuminate\Http\Request $request
	 * @param \Closure $next
	 * @param string|null $scopesString
	 *
	 * @throws \League\OAuth2\Server\Exception\InvalidScopeException
	 *
	 * @return mixed
	 */
	public function handle($request, Closure $next, $scopesString = null)
	{
		//return $request->data;

		// $data =  json_decode(base64_decode($request->data), true);
		// $request->replace($data);
		// $request->merge($data);
		// return $request->all();
		$scopes = [];

		if (!is_null($scopesString)) {
			$scopes = explode('+', $scopesString);
		}
		//$access_token = \Authorizer::issueAccessToken();
		$this->authorizer->setRequest($request);
		if($this->authorizer->validateAccessToken($this->httpHeadersOnly)){

			$this->validateScopes($scopes);
			return $next($request);
		}else{
			if(\URLFilter::filter()){
				return \Api::invalid_access_token();
			}
		};


	}

	/**
	 * Validate the scopes.
	 *
	 * @param $scopes
	 *
	 * @throws \League\OAuth2\Server\Exception\InvalidScopeException
	 */
	public function validateScopes($scopes)
	{
		if (!empty($scopes) && !$this->authorizer->hasScope($scopes)) {
			throw new InvalidScopeException(implode(',', $scopes));
		}
	}
}
