<?php

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class GuestMiddleware extends BaseMiddleware
{
	/**
	 * Pass-through guest users only.
	 * Authenticated users will be redirected to the homepage
	 *
	 * @see AuthenticatedMiddleware::class for the reverse condition
	 *
	 * @param  Request  $request
	 * @param  Response $response
	 * @param  callable $next
	 * @return Response
	 */
	public function __invoke(Request $request, Response $response, Callable $next): Response
	{
		if (!is_null($this->container->get('auth')->getLoggedInUser())) {
			return $response->withRedirect($this->container->get('router')->pathFor('home'), 301);
		}

		return $next($request, $response);;
	}
}
