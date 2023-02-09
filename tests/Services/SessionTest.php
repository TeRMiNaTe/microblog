<?php

namespace Tests\Services;

use App\Services\SessionService;
use Tests\TestCase;

class SessionTest extends TestCase
{
	/**
	 * Test that the session service is loaded
	 *
	 * @return SessionService
	 */
	public function testIsSessionLoaded(): SessionService
	{
		$session = $this->getAppInstance()->getContainer()->get('session');

		$this->assertInstanceOf(SessionService::class, $session);

		return $session;
	}

	/**
	 * @depends testIsSessionLoaded
	 */
	public function testSessionCreate(SessionService $session): void
	{
		$this->assertTrue(session_status() === PHP_SESSION_NONE);

		$session->start();

		$this->assertTrue(session_status() === PHP_SESSION_ACTIVE);
	}

	/**
	 * @depends testIsSessionLoaded
	 * @depends testSessionCreate
	 */
	public function testSessionValueStore(SessionService $session): void
	{
		$value = $this->generateRandomString();

		$this->assertFalse($session->get(self::class) === $value);

		$session->set(self::class, $value);

		$this->assertTrue($session->get(self::class) === $value);

		$session->delete(self::class);

		$this->assertFalse($session->get(self::class) === $value);
		$this->assertNull($session->get(self::class));
	}

	/**
	 * @depends testIsSessionLoaded
	 * @depends testSessionCreate
	 */
	public function testSessionDestroy(SessionService $session): void
	{
		$session->destroy();

		$this->assertTrue(session_status() === PHP_SESSION_NONE);
	}
}
