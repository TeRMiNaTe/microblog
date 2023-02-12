<?php

namespace App\Middleware;

use App\Exceptions\PublicRedirectException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class PublisherMiddleware extends BaseMiddleware
{
	/**
	 * Pass-through publisher users only.
	 * Guests or regular users will be "thrown" out or redirected
	 *
	 * @param  Request  $request
	 * @param  Response $response
	 * @param  callable $next
	 * @return Response
	 */
	public function __invoke(Request $request, Response $response, Callable $next): Response
	{
		$role_name = 'publisher';

		if (!$this->container->get('auth')->getLoggedInUser()?->hasRole($role_name)) {
			if ($request->isPost()) {
				throw new PublicRedirectException('Access Denied. You need to be a "'.$role_name.'" to perform this action');
			}
			return $response->withRedirect($this->container->get('router')->pathFor('home'), 301);
		}

		return $next($request, $response);
	}
}
