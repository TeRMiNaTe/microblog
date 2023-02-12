<?php

namespace Tests\Services;

use App\Exceptions\PublicRedirectException;
use App\Services\FileService;
use Slim\Http\UploadedFile;
use Tests\TestCase;

class FileTest extends TestCase
{
	/** @var string The subdirectory where the test files will be stored */
	private const TEST_SUBDIRECTORY = 'tests/';

	/**
	 * Test that the files service is loaded
	 *
	 * @return FileService
	 */
	public function testIsFileServiceLoaded(): FileService
	{
		$files = $this->getAppInstance()->getContainer()->get('files');

		$this->assertInstanceOf(FileService::class, $files);

		return $files;
	}

	/**
	 * @depends testIsFileServiceLoaded
	 */
	public function testGalleryFilesExist(FileService $files): UploadedFile
	{
		$this->assertNotEmpty($this->getGalleryImages());

		return $this->generateUploadedFile($this->getRandomGalleryImage());
	}

	/**
	 * @depends testIsFileServiceLoaded
	 * @depends testGalleryFilesExist
	 */
	public function testFileStorage(FileService $files, UploadedFile $file): void
	{
		$filename = $files->save($file, self::TEST_SUBDIRECTORY);

		$this->assertTrue($this->checkFileExists($files, $filename));

		$files->delete($filename, self::TEST_SUBDIRECTORY);

		$this->assertFalse($this->checkFileExists($files, $filename));
	}

	/**
	 * @depends testIsFileServiceLoaded
	 */
	public function testImageValidation(FileService $files): void
	{
		$basename = 'test.php';
		$temp = tempnam(sys_get_temp_dir(), $basename);
		$file = new UploadedFile($temp, $basename);

		$filename = $files->save($file, self::TEST_SUBDIRECTORY);

		$this->assertTrue($this->checkFileExists($files, $filename));

		$files->delete($filename, self::TEST_SUBDIRECTORY);

		$this->expectException(PublicRedirectException::class);

		$temp = tempnam(sys_get_temp_dir(), $basename);
		$file = new UploadedFile($temp, $basename);

		$files->saveImage($file, self::TEST_SUBDIRECTORY);

		// Clean up, just in case
		$files->delete($file, self::TEST_SUBDIRECTORY);
	}

	/**
	 * Check if a file exists where the FileService expects it to be
	 *
	 * @param  FileService $files
	 * @param  string      $filename
	 * @return bool
	 */
	private function checkFileExists(FileService $files, string $filename): bool
	{
		return file_exists(self::PUBLIC_PATH . $files->getUploadDir() . self::TEST_SUBDIRECTORY . $filename);
	}
}
