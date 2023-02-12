<?php

namespace App\Controllers;

use App\Models\Post;
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
		return $this->view->render($response, 'blog.twig', [
			'list' => Post::orderBy('id', 'desc')->get(),
			'image_path' => $this->container->posts->getImageDir(true),
		]);
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

	/**
	 * Display the page for editing a post
	 * If the post could not be found - redirect the user instead of throwing
	 *
	 * @param  Request  $request
	 * @param  Response $response
	 * @param  array    $arguments
	 * @return Response
	 */
	public function edit(Request $request, Response $response, array $arguments): Response
	{
		if (!$post = Post::find($arguments['id'])) {
			return $response->withRedirect($this->router->pathFor('blog'), 301);
		}

		return $this->view->render($response, 'edit.twig', [
			'post' => $post,
			'image_path' => $this->container->posts->getImageDir(true),
		]);
	}
}
