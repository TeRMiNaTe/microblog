<?php

namespace App\Services;

use App\Exceptions\PublicRedirectException;
use App\Models\User;

/**
 * Service responsisble for handling user authentication
 */
class AuthService extends BaseService
{
	/** @var User Instance of the logged in user */
	protected User $user;

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
			$this->container->get('session')->set('user', $user->only(['id', 'name']));
		}

		return $success;
	}

	/**
	 * Delete the account of the currently logged in user
	 *
	 * @throws PublicRedirectException
	 */
	public function unregister(): void
	{
		if (!$user = $this->getLoggedInUser()) {
			throw new PublicRedirectException('No current logged-in user.');
		}

		$user->roles()->detach();
		$user->delete();

		unset($this->user);

		$this->container->get('session')->destroy();
	}

	/**
	 * Get the current logged in user instance
	 *
	 * @return User|null
	 */
	public function getLoggedInUser(): ?User
	{
		if (!$user_data = $this->container->get('session')->get('user')) {
			return null;
		}

		return $this->user ??= $this->instantiateUser($user_data);
	}

	/**
	 * Create a user instance from the session data
	 *
	 * @param  array  $user_data
	 * @return User
	 */
	protected function instantiateUser(array $user_data): User
	{
		$user = new User($user_data);
		// We asssign the ID manually, as it's a guarded attribute
		$user->id = $user_data['id'];
		// Tell the ORM that this is a real record that exists in the DB
		$user->exists = true;

		return $user;
	}
}
