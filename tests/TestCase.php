<?php

namespace Tests;

use PHPUnit\Framework\TestCase as PHPUnit_TestCase;
use Slim\App;
use Slim\Http\UploadedFile;

class TestCase extends PHPUnit_TestCase
{
	/** @var string The full path to the public folder */
	protected const PUBLIC_PATH = __DIR__ . '/../public';

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

	/**
	 * Returns the list of gallery images
	 *
	 * @return array
	 */
	protected function getGalleryImages(): array
	{
		return glob(self::PUBLIC_PATH . '/gallery/*.*');
	}

	/**
	 * Returns a random gallery image
	 *
	 * @return string
	 */
	protected function getRandomGalleryImage(): string
	{
		$images = $this->getGalleryImages();

		$selected = array_rand($images);

		return $images[$selected];
	}

	/**
	 * Generates a temporary file to be used for uploads from an existing file
	 *
	 * @param  string $path_to_existing_file
	 * @return UploadedFile
	 */
	protected function generateUploadedFile(string $path_to_existing_file): UploadedFile
	{
		$basename = basename($path_to_existing_file);

		$temp = tempnam(sys_get_temp_dir(), $basename);
		file_put_contents($temp, file_get_contents("$path_to_existing_file"));

		return new UploadedFile($temp, $basename);
	}
}
