<?php

namespace App\Controllers;

use App\Exceptions\PublicRedirectException;
use App\Services\PostService;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Controller managing blog post CRUD operations
 */
class PostController extends BaseController
{
	/** @var PostService Service managing blog posts */
	protected PostService $posts;

	/**
	 * Load the posts service
	 *
	 * @inheritDoc
	 */
	public function __construct(ContainerInterface $container)
	{
		parent::__construct($container);

		$this->posts = $this->container->get('posts');
	}

	/**
	 * Create a new post
	 * We validate and use the three required params: title, content & featured_image
	 *
	 * @param  Request  $request
	 * @param  Response $response
	 * @return Response
	 * @throws PublicRedirectException
	 */
	public function create(Request $request, Response $response): Response
	{
		$this->validatePostRequest($request);

		if ($request->getUploadedFiles()['featured_image']?->getError() !== UPLOAD_ERR_OK) {
			throw new PublicRedirectException('A featured image is required to publish a post.');
		}

		$this->posts->create($request->getParams(['title', 'content']), $request->getUploadedFiles()['featured_image']);

		return $response->withRedirect($this->router->pathFor('blog'), 301);
	}

	/**
	 * Update an existing post
	 * Always updated: title & content
	 * Optional (if provided): featured_image
	 *
	 * @param  Request  $request
	 * @param  Response $response
	 * @return
	 */
	public function update(Request $request, Response $response): Response
	{
		$this->validatePostRequest($request);

		$this->posts->update((int) $request->getParam('id'), $request->getParams(['title', 'content']), $request->getUploadedFiles()['featured_image'] ?? null);

		return $response->withRedirect($this->router->pathFor('blog'), 301);
	}

	/**
	 * Delete a post
	 *
	 * @param  Request  $request
	 * @param  Response $response
	 * @return
	 */
	public function delete(Request $request, Response $response): Response
	{
		$this->posts->delete((int) $request->getParam('id'));

		return $response->withRedirect($this->router->pathFor('blog'), 301);
	}

	/**
	 * Validate the title and content request input
	 *
	 * @param  Request $request
	 * @throws PublicRedirectException
	 */
	protected function validatePostRequest(Request $request): void
	{
		if (!is_string($request->getParam('title')) || empty($request->getParam('title'))) {
			throw new PublicRedirectException('The title you have entered is invalid.');
		}

		if (!is_string($request->getParam('content')) || empty($request->getParam('content'))) {
			throw new PublicRedirectException('It appears you haven\'t provided any content for your post. Please add some content.');
		}
	}
}
