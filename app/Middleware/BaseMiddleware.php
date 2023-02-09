<?php

namespace App\Middleware;;

use Psr\Container\ContainerInterface;

/**
 * Base Middleware Class
 */
class BaseMiddleware
{
	/** @var ContainerInterface Provides access to the Application container */
	protected ContainerInterface $container;

	/**
	 * Assign the container to allow access to the rest of the application
	 *
	 * @param ContainerInterface $container
	 */
	public function __construct(ContainerInterface $container)
	{
		$this->container = $container;
	}
}
