<?php

namespace Tests\Services;

use App\Services\HashService;
use Illuminate\Contracts\Hashing\Hasher;
use Tests\TestCase;

class HashingTest extends TestCase
{
	/** @var string The default pepper value (used for generating secure passwords). Taken from env.php.dist */
	protected const DEFAULT_PEPPER = 'I_DO_NOT_SECURE_MY_PASSWORDS';

	/**
	 * Test that the hasher service is loaded
	 *
	 * @return HashService
	 */
	public function testIsHasherLoaded(): HashService
	{
		$hasher = $this->getAppInstance()->getContainer()->get('hasher');

		$this->assertInstanceOf(Hasher::class, $hasher);
		$this->assertInstanceOf(HashService::class, $hasher);

		return $hasher;
	}

	/**
	 * @depends testIsHasherLoaded
	 */
	public function testHashing(HashService $hasher): void
	{
		for ($i = 0; $i < 3; ++$i) {
			$value = $this->generateRandomString();

			$this->assertTrue($hasher->check($value, $hasher->make($value)));
		}
	}

	/**
	 * @depends testIsHasherLoaded
	 */
	public function testPepperIsChanged(HashService $hasher): void
	{
		$value = $this->generateRandomString();

		$hasher_with_default_pepper = new HashService($this->getAppInstance()->getContainer(), ['pepper' => self::DEFAULT_PEPPER]);

		$this->assertTrue($hasher->check($value, $hasher->make($value)));
		$this->assertFalse($hasher->check($value, $hasher_with_default_pepper->make($value)));
	}

	/**
	 * @depends testIsHasherLoaded
	 */
	public function testNeedsRehash(HashService $hasher): void
	{
		$value = $this->generateRandomString();

		$hashed_value = $hasher->make($value, ['cost' => 10]);

		$this->assertTrue($hasher->needsRehash($hashed_value, ['cost' => 12]));
		$this->assertFalse($hasher->needsRehash($hashed_value, ['cost' => 10]));
	}
}
