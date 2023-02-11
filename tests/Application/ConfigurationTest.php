<?php

namespace Tests\Application;

use Illuminate\Database\Capsule\Manager;
use Psr\Log\LoggerInterface;
use Slim\Views\Twig;
use Tests\TestCase;

class ConfigurationTest extends TestCase
{
	/** @var array Required application dependencies */
	protected const REQUIRED_DEPENDENCIES = [
		'db'     => Manager::class,
		'logger' => LoggerInterface::class,
		'view'   => Twig::class,
	];

	/**
	 * @return void
	 */
	public function testDependenciesAreLoaded(): void
	{
		$container = $this->getAppInstance()->getContainer();

		foreach (self::REQUIRED_DEPENDENCIES as $key => $dependency) {
			$this->assertInstanceOf($dependency, $container[$key]);
		}
	}

	/**
	 * @return void
	 */
	public function testDatabaseConnection(): void
	{
		$result = $this->getAppInstance()->getContainer()->get('db')->getConnection()->query()->selectRaw('1')->first();

		$this->assertNotEmpty($result);
	}

	/**
	 * @return void
	 */
	public function testTestEmailIsConfigured(): void
	{
		$this->assertNotEmpty($this->getTestEmail());
	}

}
