<?php

namespace App\Services;

use App\Exceptions\PublicRedirectException;
use App\Models\Post;
use Psr\Container\ContainerInterface;
use Slim\Http\UploadedFile;

/**
 * Not that kind of post service...
 * Service responsisble for managing blog posts
 */
class PostService extends BaseService
{
	/**
	 * Creates a new blog post
	 * If the author is not provided, the current logged in user will be used
	 *
	 * @param  array        $blog_data
	 * @param  UploadedFile $image
	 * @param  int|null     $id_author
	 * @return Post
	 */
	public function create(array $blog_data, UploadedFile $image, ?int $id_author = null): Post
	{
		$featured_image = $this->container->get('files')->saveImage($image, $this->getImageDir());

		return Post::create([
			...$blog_data,
			'featured_image' => $featured_image,
			'id_author' => $id_author ?? $this->getDefaultAuthorID(),
		]);
	}

	/**
	 * Updates an existing blog post
	 * The featured image update is optional (only if provided)
	 *
	 * @param  int               $id
	 * @param  array             $blog_data
	 * @param  UploadedFile|null $image
	 * @return void
	 */
	public function update(int $id, array $blog_data, ?UploadedFile $image = null): void
	{
		if (!$post = Post::find($id)) {
			throw new PublicRedirectException('Failed to update post. Post with ID #'.$id.' not found.');
		}

		$post->fill($blog_data);

		if (!is_null($image) && $image->getError() === UPLOAD_ERR_OK) {
			$featured_image = $this->container->get('files')->saveImage($image, $this->getImageDir());

			// The old post image is deleted as we no longer have a use for it
			$this->container->get('files')->delete($post->featured_image, $this->getImageDir());

			$post->featured_image = $featured_image;
		}

		$post->save();
	}

	/**
	 * Deletes an existing blog pos
	 *
	 * @param  int    $id
	 * @return void
	 */
	public function delete(int $id): void
	{
		if (!$post = Post::find($id)) {
			throw new PublicRedirectException('Failed to delete post. Post with ID #'.$id.' not found.');
		}

		$post->delete();
	}

	/**
	 * Get the relative directory where featured images for posts will be stored
	 * Use a default value if not provided in the config
	 *
	 * @param  bool  $full_path
	 * @return string
	 */
	public function getImageDir(bool $full_path = false): string
	{
		return ($full_path ? $this->container->get('files')->getUploadDir() : '') . ($this->config['image_upload_dir'] ?? 'posts') . DIRECTORY_SEPARATOR;
	}

	/**
	 * Get the default post author
	 * Currently this defaults to the logged in user
	 *
	 * @return int|null
	 */
	protected function getDefaultAuthorID(): ?int
	{
		return $this->container->get('auth')->getLoggedInUser()?->id;
	}
}
