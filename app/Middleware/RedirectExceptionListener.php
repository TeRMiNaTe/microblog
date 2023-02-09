<?php

namespace App\Middleware;

use App\Exceptions\PublicRedirectException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * PublicRedirectException Listener
 *
 * Sets the Exception message in the session and -
 * Redirects the user back to the referrer (or home) page
 *
 */
class RedirectExceptionListener extends BaseMiddleware
{
	/**
	 * Listen for PublicRedirectException throws
	 *
	 * @param  Request  $request
	 * @param  Response $response
	 * @param  callable $next
	 */
	public function __invoke(Request $request, Response $response, Callable $next): Response
	{
		try {
			$response = $next($request, $response);
		} catch (PublicRedirectException $exception) {
			$this->container->get('session')->set(PublicRedirectException::class, $exception->getMessage());

			if ($referers = $request->getHeader('HTTP_REFERER')) {
				$back = array_shift($referers);
			} else {
				$back = $this->container->get('router')->pathFor('home');
			}

			return $response->withRedirect($back, 301);
		}

		return $response;
	}
}
