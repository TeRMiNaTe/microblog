<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Controller managing the display of blog posts
 */
class BlogController extends BaseController
{
	/**
	 * Display a list of blog posts
	 *
	 * @param  Request  $request
	 * @param  Response $response
	 * @return Response
	 */
	public function list(Request $request, Response $response): Response
	{
		return $this->view->render($response, 'blog.twig');
	}

	/**
	 * Display the page for publishing a new post
	 *
	 * @param  Request  $request
	 * @param  Response $response
	 * @return Response
	 */
	public function publish(Request $request, Response $response): Response
	{
		return $this->view->render($response, 'publish.twig');
	}
}
