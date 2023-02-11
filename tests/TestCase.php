<?php

namespace Tests;

use PHPUnit\Framework\TestCase as PHPUnit_TestCase;
use Slim\App;

class TestCase extends PHPUnit_TestCase
{
	/**
	 * @return string
	 */
	protected function getTestEmail(): string
	{
		$env = require __DIR__ . '/../env.php';

		return $env['tests']['email'];
	}

	/**
	 * @return App
	 * @throws Exception
	 */
	protected function getAppInstance(): App
	{
		require __DIR__ . '/../bootstrap/app.php';

		return $app;
	}

	/**
	 * Generate a random string with numbers
	 * Taken from @link https://stackoverflow.com/a/13212994/2285615
	 *
	 * @param  int $length
	 * @return string
	 */
	protected function generateRandomString(int $length = 10): string
	{
		return substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
	}
}
