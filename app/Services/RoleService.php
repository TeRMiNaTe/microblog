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
	/** @var User Instance of the logged in user */
	protected User $user;

	/**
	 * Assigns and validates the logged in user
	 *
	 * @inheritDoc
	 */
	public function __construct(ContainerInterface $container, ?array $config)
	{
		parent::__construct($container, $config);

		if (!$user = $this->container->get('auth')->getLoggedInUser()) {
			throw new PublicRedirectException('Unable to interact with roles. No logged in user.');
		}

		$this->user = $user;
	}

	/**
	 * Grant a role to the logged in user
	 *
	 * @param  string $name
	 * @return void
	 */
	public function grant(string $name): void
	{
		$role = $this->findRole($name);

		if ($this->user->hasRole($role->name)) {
			throw new PublicRedirectException('Unable to grant "'.$role->name.'" role. Already exists.');
		}

		$this->user->roles()->attach($role->id);
	}

	/**
	 * Remove a role from the logged in user
	 *
	 * @param  string $name
	 * @return void
	 */
	public function revoke(string $name): void
	{
		$role = $this->findRole($name);

		if (!$this->user->hasRole($role->name)) {
			throw new PublicRedirectException('Unable to revoke "'.$role->name.'" role. You do not have this role.');
		}

		$this->user->roles()->detach($role->id);
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
