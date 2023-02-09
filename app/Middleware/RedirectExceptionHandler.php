<?php

namespace App\Middleware;;

use App\Exceptions\PublicRedirectException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Handle PublicRedirectException messages
 */
class RedirectExceptionHandler extends BaseMiddleware
{
	/**
	 * Listen for PublicRedirectException messages and pass them on to the view
	 * Since these are single-time events, we clear the message afterwards
	 *
	 * @param  Request  $request
	 * @param  Response $response
	 * @param  callable $next
	 */
	public function __invoke(Request $request, Response $response, Callable $next): Response
	{
		if ($message = $this->container->get('session')->get(PublicRedirectException::class)) {
			$this->container->get('session')->delete(PublicRedirectException::class);
			$this->container->get('view')->offsetSet('redirect_error', $message);
		}

		return $next($request, $response);
	}
}
