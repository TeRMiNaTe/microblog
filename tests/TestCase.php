<?php

namespace Tests;

use PHPUnit\Framework\TestCase as PHPUnit_TestCase;
use Slim\App;

class TestCase extends PHPUnit_TestCase
{
	/**
	 * @return App
	 * @throws Exception
	 */
	protected function getAppInstance(): App
	{
		require __DIR__ . '/../bootstrap/app.php';

		return $app;
	}
}
