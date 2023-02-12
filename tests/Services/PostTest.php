<?php

namespace Tests\Services;

use App\Exceptions\PublicRedirectException;
use App\Services\PostService;
use App\Models\Post;
use Tests\TestCase;

class PostTest extends TestCase
{
	/** @var string Default post title */
	protected const POST_DEFAULT_TITLE = 'Some title';

	/** @var string Default post content */
	protected const POST_DEFAULT_CONTENT = 'Some content';

	/**
	 * Test that the posts service is loaded
	 *
	 * @return PostService
	 */
	public function testIsPostServiceLoaded(): PostService
	{
		$posts = $this->getAppInstance()->getContainer()->get('posts');

		$this->assertInstanceOf(PostService::class, $posts);

		return $posts;
	}

	/**
	 * @depends testIsPostServiceLoaded
	 */
	public function testPostIsCreated(PostService $posts): Post
	{
		$post = $posts->create([
			'title' => self::POST_DEFAULT_TITLE,
			'content' => self::POST_DEFAULT_CONTENT,
		], $this->generateUploadedFile($this->getRandomGalleryImage()));

		$this->assertInstanceOf(Post::class, $post);
		$this->assertSame($post->title, self::POST_DEFAULT_TITLE);
		$this->assertSame($post->content, self::POST_DEFAULT_CONTENT);

		return $post;
	}

	/**
	 * @depends testIsPostServiceLoaded
	 * @depends testPostIsCreated
	 */
	public function testImageDirPath(PostService $posts, Post $post): void
	{
		$this->assertTrue(file_exists(self::PUBLIC_PATH . $posts->getImageDir(true) . $post->featured_image));
	}

	/**
	 * @depends testIsPostServiceLoaded
	 * @depends testPostIsCreated
	 */
	public function testPostIsUpdated(PostService $posts, Post $post): Post
	{
		$updated_post = $posts->update($post->id, [
			'title' => 'Some other title',
			'content' => 'Some other content',
		]);

		$this->assertNotSame($updated_post->title, $post->title);
		$this->assertNotSame($updated_post->content, $post->content);
		$this->assertSame($updated_post->featured_image, $post->featured_image);

		$updated_post = $posts->update($post->id, [
			'title' => self::POST_DEFAULT_TITLE,
			'content' => self::POST_DEFAULT_CONTENT,
		], $this->generateUploadedFile($this->getRandomGalleryImage()));

		$this->assertSame($updated_post->title, self::POST_DEFAULT_TITLE);
		$this->assertSame($updated_post->content, self::POST_DEFAULT_CONTENT);
		$this->assertNotSame($updated_post->featured_image, $post->featured_image);

		return $updated_post;
	}

	/**
	 * @depends testIsPostServiceLoaded
	 * @depends testPostIsCreated
	 */
	public function testPostIsDeleted(PostService $posts, Post $post): void
	{
		$posts->delete($post->id);

		$this->assertNull(Post::find($post->id));
	}

	/**
	 * @depends testIsPostServiceLoaded
	 */
	public function testPostUpdateException(PostService $posts): void
	{
		$this->expectException(PublicRedirectException::class);

		$posts->update(0, [
			'title' => 'Some other title',
			'content' => 'Some other content',
		]);
	}

	/**
	 * @depends testIsPostServiceLoaded
	 */
	public function testPostDeleteException(PostService $posts): void
	{
		$this->expectException(PublicRedirectException::class);

		$posts->delete(0);
	}

}
