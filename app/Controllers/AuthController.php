<?php

namespace App\Controllers;

use App\Exceptions\PublicRedirectException;
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
	 * @return Response
	 */
	public function login(Request $request, Response $response): Response
	{
		return $this->view->render($response, 'home.twig', [
			'name' => $this->container->get('session')->get('user', 'name'),
		]);
	}

	/**
	 * User registration page
	 *
	 * @param  Request  $request
	 * @param  Response $response
	 * @return Response
	 */
	public function register(Request $request, Response $response): Response
	{
		return $this->view->render($response, 'register.twig');
	}

	/**
	 * Handle user registration
	 *
	 * @param  Request  $request
	 * @param  Response $response
	 * @return Response
	 */
	public function handleRegister(Request $request, Response $response): Response
	{
		$this->validateRegistrationRequest($request);

		$user = $this->container->get('auth')->register($request->getParam('email'), $request->getParam('password'), $request->getParams(['name']));

		$this->container->get('auth')->login(...$request->getParams(['email', 'password']));

		return $response->withRedirect($this->router->pathFor('home'), 301);
	}

	/**
	 * Handle user login
	 *
	 * @param  Request  $request
	 * @param  Response $response
	 * @return Response
	 */
	public function handleLogin(Request $request, Response $response): Response
	{
		$this->container->get('auth')->login(...$request->getParams(['email', 'password']));

		return $response->withRedirect($this->router->pathFor('home'), 301);
	}

	/**
	 * Handle user logout
	 *
	 * @param  Request  $request
	 * @param  Response $response
	 * @return Response
	 */
	public function handleLogout(Request $request, Response $response): Response
	{
		$this->container->get('session')->destroy();

		return $response->withRedirect($this->router->pathFor('home'), 301);
	}

	/**
	 * Validate the input for the registration form
	 * We try not to be too strict, but we still want some valid data
	 *
	 * @param  Request $request
	 * @return void
	 */
	protected function validateRegistrationRequest(Request $request): void
	{
		if (!filter_var($request->getParam('email'), FILTER_VALIDATE_EMAIL)) {
			throw new PublicRedirectException('Invalid email format');
		}

		if ($request->getParam('password') != $request->getParam('repeat_password')) {
			throw new PublicRedirectException('Both passwords must match.');
		}

		if (!is_string($request->getParam('name')) || empty($request->getParam('name'))) {
			throw new PublicRedirectException('The name you have entered is invalid.');
		}

		if (!is_string($request->getParam('password')) || strlen($request->getParam('password')) < 6) {
			throw new PublicRedirectException('The password you have entered is invalid or too short. Please use more than 6 characters so the hackers can\'t claim credit for your blog posts.');
		}
	}
}
