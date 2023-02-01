<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Homepage Controller
 */
class HomeController
{
	/**
	 * Sample Controller action
	 *
	 * @param  Request  $request
	 * @param  Response $response
	 * @param  array    $args
	 * @return Response
	 */
	public function home(Request $request, Response $response, array $args): Response
	{
		$response->getBody()->write("Hello");

		return $response;
	}
}
