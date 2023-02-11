<?php

namespace Tests\Services;

use App\Exceptions\PublicRedirectException;
use App\Models\User;
use App\Services\AuthService;
use Tests\TestCase;
use Throwable;

/**
 * This is a full test of the authentication system
 * Ideally it should be using Faker(s) and Mocker(s) -
 * But in the end, the best test is to actually go through the real flow
 */
class AuthenticationTest extends TestCase
{
	/**
	 * Test that the auth service is loaded
	 *
	 * @return AuthService
	 */
	public function testIsAuthServiceLoaded(): AuthService
	{
		$auth = $this->getAppInstance()->getContainer()->get('auth');

		$this->assertInstanceOf(AuthService::class, $auth);

		return $auth;
	}

	/**
	 * @depends testIsAuthServiceLoaded
	 */
	public function testDryAuthentication(AuthService $auth): void
	{
		$password = $this->generateRandomString();
		$another_password = $this->generateRandomString();

		$user = new User([
			'email'    => $this->getTestEmail(),
			'password' => $this->getAppInstance()->getContainer()->get('hasher')->make($password),
		]);

		$this->assertTrue($auth->attempt($user, $password));
		$this->assertFalse($auth->attempt($user, $another_password));
	}

	/**
	 * @depends testIsAuthServiceLoaded
	 */
	public function testAuthRegister(AuthService $auth): string
	{
		$password = $this->generateRandomString();

		$user = $auth->register($this->getTestEmail(), $password, []);

		$this->assertTrue($this->checkUserExists($this->getTestEmail()));

		return $password;
	}

	/**
	 * @depends testIsAuthServiceLoaded
	 * @depends testAuthRegister
	 */
	public function testAuthLogin(AuthService $auth, string $password): string
	{
		$auth->login($this->getTestEmail(), $password);

		$this->assertInstanceOf(User::class, $auth->getLoggedInUser());

		return $password;
	}

	/**
	 * @depends testIsAuthServiceLoaded
	 * @depends testAuthLogin
	 */
	public function testAuthLoginEmailValidation(AuthService $auth, string $password): void
	{
		$this->expectException(PublicRedirectException::class);
		$auth->login('test2@email.com', $password);
	}

	/**
	 * @depends testIsAuthServiceLoaded
	 * @depends testAuthLogin
	 */
	public function testAuthLoginPasswordValidation(AuthService $auth, string $password): void
	{
		$this->expectException(PublicRedirectException::class);
		$auth->login($this->getTestEmail(), $this->generateRandomString());
	}

	/**
	 * @depends testIsAuthServiceLoaded
	 * @depends testAuthLogin
	 */
	public function testAuthDuplicateEmailOnRegisterValidation(AuthService $auth, string $password): void
	{
		$this->expectException(PublicRedirectException::class);
		$auth->register($this->getTestEmail(), $this->generateRandomString(), []);
	}

	/**
	 * @depends testIsAuthServiceLoaded
	 * @depends testAuthLogin
	 */
	public function testAuthAccountDeletion(AuthService $auth, string $password): void
	{
		$auth->unregister();

		$this->isNull($auth->getLoggedInUser());
		$this->assertFalse($this->checkUserExists($this->getTestEmail()));
	}

	/**
	 * Check if a user with the given email exists
	 *
	 * @param  string $email
	 * @return
	 */
	protected function checkUserExists(string $email): bool
	{
		return User::where('email', $email)->count() > 0;
	}

	/**
	 * Clean up the test user from the database
	 */
	protected function onNotSuccessfulTest(Throwable $t): never
	{
		User::where(['email' => $this->getTestEmail()])->delete();
	}
}
