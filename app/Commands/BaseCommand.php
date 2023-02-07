<?php

namespace App\Commands;

use Psr\Container\ContainerInterface;

abstract class BaseCommand
{
	/** @var ContainerInterface Provides access to the Application container */
	protected ContainerInterface $container;

	/**
	 * Constructor
	 *
	 * @param ContainerInterface $container
	 */
	public function __construct(ContainerInterface $container)
	{
		$this->container = $container;
	}

	/**
	 * The bussiness logic of the command
	 *
	 * @param  array  $args The CLI arguments passed to the command
	 * @return string        The output of the command
	 */
	abstract public function command(array $args): string;
}
