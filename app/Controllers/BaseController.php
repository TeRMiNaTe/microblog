<?php

namespace App\Controllers;

use Psr\Container\ContainerInterface;
use Slim\Views\Twig;

/**
 * Base Controller class
 */
class BaseController
{
	/** @var Twig Shortcut to the view component */
	protected Twig $view;

	/** @var ContainerInterface The application container */
	protected ContainerInterface $container;

	/**
	 * We use the container to assign some useful properties
	 *
	 * @param ContainerInterface $container
	 */
	public function __construct(ContainerInterface $container)
	{
		$this->container = $container;

		$this->view = $this->container->get('view');
	}
}
