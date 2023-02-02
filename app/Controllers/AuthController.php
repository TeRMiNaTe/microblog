<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Controller for handling user authentication
 */
class AuthController extends BaseController
{
	/**
	 * Homepage / user login page
	 *
	 * @param  Request  $request
	 * @param  Response $response
	 * @param  array    $args
	 * @return Response
	 */
	public function login(Request $request, Response $response, array $args): Response
	{
		return $this->view->render($response, 'home.twig', [
			// 'name' => $args['name']
		]);
	}

	/**
	 * User registration page
	 *
	 * @param  Request  $request
	 * @param  Response $response
	 * @param  array    $args
	 * @return Response
	 */
	public function register(Request $request, Response $response, array $args): Response
	{
		return $this->view->render($response, 'register.twig', [
			// 'name' => $args['name']
		]);
	}
}
