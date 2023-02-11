<?php

namespace App\Services;

use App\Exceptions\PublicRedirectException;
use App\Models\Role;
use App\Models\User;
use Psr\Container\ContainerInterface;

/**
 * Service responsisble for managing user roles
 */
class RoleService extends BaseService
{
	/**
	 * Grant a role to a user
	 *
	 * @param  User $user
	 * @param  string $name
	 * @return void
	 */
	public function grant(User $user, string $name): void
	{
		$role = $this->findRole($name);

		if ($user->hasRole($role->name)) {
			throw new PublicRedirectException('Unable to grant "'.$role->name.'" role. Already exists.');
		}

		$user->roles()->attach($role->id);
	}

	/**
	 * Remove a role from a user
	 *
	 * @param  User $user
	 * @param  string $name
	 * @return void
	 */
	public function revoke(User $user, string $name): void
	{
		$role = $this->findRole($name);

		if (!$user->hasRole($role->name)) {
			throw new PublicRedirectException('Unable to revoke "'.$role->name.'" role. Already revoked.');
		}

		$user->roles()->detach($role->id);
	}

	/**
	 * Validate and return a role by its name
	 *
	 * @param  string $name
	 * @return Role
	 */
	protected function findRole(string $name): Role
	{
		if (!$role = Role::where(['name' => $name])->first()) {
			throw new PublicRedirectException('Unable to find role named '.$name.'.');
		}

		return $role;
	}
}
