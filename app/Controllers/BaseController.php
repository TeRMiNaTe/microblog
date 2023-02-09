<?php

namespace App\Controllers;

use Psr\Container\ContainerInterface;
use Slim\Router;
use Slim\Views\Twig;

/**
 * Base Controller class
 */
class BaseController
{
	/** @var ContainerInterface The application container */
	protected ContainerInterface $container;

	/** @var Twig Shortcut to the view component */
	protected Twig $view;

	/** @var Router The application router */
	protected Router $router;

	/**
	 * We use the container to assign some useful properties
	 *
	 * @param ContainerInterface $container
	 */
	public function __construct(ContainerInterface $container)
	{
		$this->container = $container;

		$this->view = $this->container->get('view');

		$this->router = $this->container->get('router');
	}
}
