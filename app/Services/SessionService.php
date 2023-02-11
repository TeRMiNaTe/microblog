<?php

namespace App\Services;

/**
 * Service responsisble for handling session data
 */
class SessionService extends BaseService
{
	/**
	 * Start the current session
	 *
	 * @return void
	 */
	public function start(): void
	{
		if (session_status() === PHP_SESSION_NONE) {
			session_start();
		}
	}

	/**
	 * Store a value in the session
	 *
	 * @param string $key
	 * @param mixed  $value
	 */
	public function set(string $key, $value): void
	{
		$_SESSION[$key] = $value;
	}

	/**
	 * Retrieve a value from the session by it's key
	 *
	 * @param string      $key
	 * @param string|null $subkey
	 */
	public function get(string $key, ?string $subkey = null)
	{
		if (!is_null($subkey)) {
			return $_SESSION[$key][$subkey] ?? null;
		}

		return $_SESSION[$key] ?? null;
	}

	/**
	 * Remove a value in the session
	 *
	 * @param string      $key
	 * @param string|null $subkey
	 * @return void
	 */
	public function delete(string $key, ?string $subkey = null): void
	{
		if (!is_null($subkey)) {
			unset($_SESSION[$key][$subkey]);
		}

		unset($_SESSION[$key]);
	}

	/**
	 * Destroy the current session
	 *
	 * @return void
	 */
	public function destroy(): void
	{
		$_SESSION = [];

		if (session_status() == PHP_SESSION_ACTIVE) {
			session_destroy();
		}
	}
}
