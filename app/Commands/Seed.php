<?php

namespace App\Commands;

use App\Models\Role;
use Database\Seeders\UserRoleSeeder;
use Illuminate\Database\Capsule\Manager as Capsule;
use RuntimeException;

class Seed extends BaseCommand
{
	/**
	 * Seeds the database with the required data needed to run the application
	 *
	 * @inheritDoc
	 */
	public function command(array $args): string
	{
		if (!Capsule::schema()->hasTable('roles')) {
			throw new RuntimeException('Table not found: "roles". Please run a migration to create it first. Syntax: "php .\public\index.php migrate {command} {migration_name}"');
		}

		if (empty(Role::count())) {
			$seeder = new UserRoleSeeder();

			$seeder->run();
		}

		return "Done.\n";
	}
}
