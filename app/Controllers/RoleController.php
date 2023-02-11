<?php

namespace App\Controllers;

use App\Exceptions\PublicRedirectException;
use App\Services\RoleService;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Controller managing role-related actions
 */
class RoleController extends BaseController
{
	/** @var RoleService Service managing user roles */
	protected RoleService $roles;

	/**
	 * Load the role service
	 *
	 * @inheritDoc
	 */
	public function __construct(ContainerInterface $container)
	{
		parent::__construct($container);

		$this->roles = $this->container->get('roles');
	}

	/**
	 * Grant or revoke a user role
	 *
	 * @param  Request  $request
	 * @param  Response $response
	 * @param  array    $arguments
	 * @return Response
	 */
	public function manage(Request $request, Response $response, array $arguments): Response
	{
		if ($request->getParam('action') == 'apply') {
			$this->roles->grant($arguments['name']);
		} elseif ($request->getParam('action') == 'revoke') {
			$this->roles->revoke($arguments['name']);
		} else {
			throw new PublicRedirectException('Unrecognized role action');
		}

		return $response->withRedirect($this->router->pathFor('publish'), 301);
	}
}
