<?php

namespace App\Services;

use App\Exceptions\PublicRedirectException;
use App\Models\User;

/**
 * Service responsisble for handling user authentication
 */
class AuthService extends BaseService
{
	/**
	 * Create a new user account
	 *
	 * @param  array  $user_data
	 * @param  string $password
	 * @return User
	 * @throws PublicRedirectException
	 */
	public function register(string $email, string $password, array $user_data): User
	{
		if (User::where(['email' => $email])->first()) {
			throw new PublicRedirectException('A user with this email already exists.');
		}

		return User::create([
			...$user_data,
			'email'    => $email,
			'password' => $this->container->get('hasher')->make($password),
		]);
	}

	/**
	 * Login a user with an email and password
	 *
	 * @param  User   $user
	 * @param  string $password
	 * @throws PublicRedirectException
	 */
	public function login(string $email, string $password): void
	{
		if (!$user = User::where(['email' => $email])->first()) {
			throw new PublicRedirectException('Invalid login credentials.');
		}

		if (!$this->attempt($user, $password)) {
			throw new PublicRedirectException('Invalid login credentials.');
		}
	}

	/**
	 * Attempt to login a user using the provided password
	 *
	 * @param  User   $user
	 * @param  string $password
	 * @return bool
	 */
	public function attempt(User $user, string $password): bool
	{
		if ($success = $this->container->get('hasher')->check($password, $user->password)) {
			$this->container->get('session')->set('user', $user->only(['name']));
		}

		return $success;
	}
}
