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
	 * @param  array    $args
	 * @return Response
	 */
	public function list(Request $request, Response $response, array $args): Response
	{
		return $this->view->render($response, 'blog.twig', [
			// 'name' => $args['name']
		]);
	}
}
