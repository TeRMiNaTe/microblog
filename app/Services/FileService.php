<?php

namespace App\Services;

use App\Exceptions\PublicRedirectException;
use Slim\Http\UploadedFile;

/**
 * Service responsisble for managing files
 */
class FileService extends BaseService
{
	/** @var string The full path to the public folder */
	protected const PUBLIC_DIRECTORY_PATH = __DIR__ . '/../../public';

	/**
	 * Stores an uploaded file into the storage system
	 * If a basename isn't provided, a unique random one will be generated
	 *
	 * @param  UploadedFile $file
	 * @param  string|null  $subdirectory
	 * @param  string|null  $basename
	 * @return string
	 */
	public function save(UploadedFile $file, ?string $subdirectory = null, ?string $basename = null): string
	{
		$extension = pathinfo($file->getClientFilename(), PATHINFO_EXTENSION);

		$basename = $basename ?? $this->generateNewFileName($extension, $subdirectory);

		$filename = $basename . '.' . $extension;

		$this->upload($file, $filename, $subdirectory);

		return $filename;
	}

	/**
	 * Stores an image file into the storage system
	 * The file format is validated first
	 *
	 * @param  UploadedFile $file
	 * @param  string|null  $subdirectory
	 * @param  string|null  $basename
	 * @return string
	 */
	public function saveImage(UploadedFile $file, ?string $subdirectory = null, ?string $basename = null): string
	{
		$this->validateFileIsImage($file);

		return $this->save($file, $subdirectory, $basename);
	}

	/**
	 * Deletes a file from the storage system
	 *
	 * @param  string      $filename
	 * @param  string|null $subdirectory
	 * @return bool
	 */
	public function delete(string $filename, ?string $subdirectory = null): bool
	{
		return unlink($this->getFileDirectory($subdirectory) . $filename);
	}

	/**
	 * Get the relative directory where files will be stored
	 * Use a default value if not provided in the config
	 *
	 * @return string
	 */
	public function getUploadDir(): string
	{
		return DIRECTORY_SEPARATOR . ($this->config['upload_dir'] ?? 'uploads') . DIRECTORY_SEPARATOR;
	}

	/**
	 * Generates a new unique name for the uploaded file
	 * Makes sure to not conflict with already existing files in the same directory
	 *
	 * @param  string      $extension
	 * @param  string|null $subdirectory
	 * @return string
	 */
	protected function generateNewFileName(string $extension, ?string $subdirectory = null): string
	{
		$file_directory = $this->getFileDirectory($subdirectory);

		do {
			$name = bin2hex(random_bytes(24));
		} while (file_exists($file_directory . $name . '.' . $extension));

		return $name;
	}

	/**
	 * Uploads or moves a file to the storage system
	 * Will create the directory if it does not already exist
	 *
	 * @param  UploadedFile $file
	 * @param  string       $filename
	 * @param  string|null  $subdirectory
	 * @return void
	 */
	protected function upload(UploadedFile $file, string $filename, ?string $subdirectory = null): void
	{
		$dir = $this->getFileDirectory($subdirectory);

		if (!is_writable($dir)) {
			mkdir($dir, 0755, true);
		}

		$file->moveTo($this->getFileDirectory($subdirectory) . $filename);
	}

	/**
	 * Validate the type of an uploaded file as an image
	 *
	 * @param  UploadedFile $file
	 * @return void
	 */
	protected function validateFileIsImage(UploadedFile $file): void
	{
		if (strstr(mime_content_type($file->file), 'image/') === false) {
			throw new PublicRedirectException('The provided file could not be recognized as an image. Please use a different one.');
		}
	}

	/**
	 * Builds and returns a storage path for a file
	 *
	 * @param  string|null $subdirectory
	 * @return string
	 */
	private function getFileDirectory(?string $subdirectory = null): string
	{
		$file_directory = self::PUBLIC_DIRECTORY_PATH . $this->getUploadDir();

		if (!is_null($subdirectory)) {
			$file_directory .= $subdirectory;
		}

		return $file_directory;
	}
}
