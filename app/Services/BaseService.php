<?php

namespace App\Services;

use Psr\Container\ContainerInterface;

class BaseService
{
	/** @var ContainerInterface The application container */
	protected ContainerInterface $container;

	/** @var array Configuration for the specific service */
	protected ?array $config;

	/**
	 * Assign the container to allow access to the rest of the application
	 * Assign configuration for the service
	 *
	 * @param ContainerInterface $container
	 */
	public function __construct(ContainerInterface $container, ?array $config)
	{
		$this->container = $container;

		$this->config = $config;
	}
}
