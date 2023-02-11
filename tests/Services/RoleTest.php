<?php

namespace Tests\Services;

use App\Exceptions\PublicRedirectException;
use App\Models\User;
use App\Models\Role;
use App\Services\RoleService;
use Tests\TestCase;

class RoleTest extends TestCase
{
	/**
	 * Test that the roles service is loaded
	 *
	 * @return RoleService
	 */
	public function testIsRoleServiceLoaded(): RoleService
	{
		$roles = $this->getAppInstance()->getContainer()->get('roles');

		$this->assertInstanceOf(RoleService::class, $roles);

		return $roles;
	}

	/**
	 * Test that roles exist in the DB
	 *
	 * @return void
	 */
	public function testRolesExist(): void
	{
		$this->assertGreaterThan(0, Role::count());
	}

	/**
	 * @depends testIsRoleServiceLoaded
	 * @depends testRolesExist
	 */
	public function testRoleManagement(RoleService $roles): void
	{
		$role = Role::inRandomOrder()->first();

		$user = User::firstOrCreate([
			'email' => $this->getTestEmail(),
		]);

		$this->assertFalse($user->hasRole($role->name));

		$roles->grant($user, $role->name);

		$this->assertTrue($user->hasRole($role->name));

		$roles->revoke($user, $role->name);

		$this->assertFalse($user->hasRole($role->name));
	}

	/**
	 * @depends testIsRoleServiceLoaded
	 * @depends testRolesExist
	 */
	public function testRoleAlreadyGranted(RoleService $roles): void
	{
		$role = Role::inRandomOrder()->first();

		$user = User::firstOrCreate([
			'email' => $this->getTestEmail(),
		]);

		$roles->grant($user, $role->name);

		$this->expectException(PublicRedirectException::class);

		$roles->grant($user, $role->name);
	}

	/**
	 * @depends testIsRoleServiceLoaded
	 * @depends testRolesExist
	 */
	public function testRoleAlreadyRevoked(RoleService $roles): void
	{
		$role = Role::inRandomOrder()->first();

		$user = User::firstOrCreate([
			'email' => $this->getTestEmail(),
		]);

		$this->expectException(PublicRedirectException::class);

		$roles->revoke($user, $role->name);
	}

	/**
	 * Clean up the test user and roles from the database
	 */
	protected function tearDown(): void
	{
		if ($user = User::where(['email' => $this->getTestEmail()])->first()) {
			$user->roles()->detach();
			$user->delete();
		}
	}
}
