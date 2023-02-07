<?php

namespace App\Commands;

use Illuminate\Database\Migrations\Migration;
use RuntimeException;

class Migrate extends BaseCommand
{
	/** @var string Path to the migration classes */
	protected const MIGRATION_PATH = __DIR__.'/../../database/migrations';

	/** @var array List of supported migration commands. Each command should have a method mapped to it (@see self::getCommandMethod()) */
	protected const COMMAND_LIST = ['run', 'rollback', 'refresh'];

	/** @var Migration An instance of the migration being handled */
	protected Migration $migration;

	/**
	 * Runs one of the supported commands on a given migration
	 *
	 * @inheritDoc
	 */
	public function command(array $args): string
	{
		if (count($args) !== 2) {
			throw new RuntimeException('Invalid command usage. Syntax: "php .\public\index.php migrate {command} {migration_name}"');
		}

		[$command_name, $migration_name] = $args;

		if (!in_array($command_name, self::COMMAND_LIST)) {
			throw new RuntimeException('Unsupported command. List of available commands: '.implode(', ', self::COMMAND_LIST));
		}

		$method = $this->getCommandMethod($command_name);
		if (!method_exists(__CLASS__, $method)) {
			throw new RuntimeException('Issue with command configuration. Please check your configuration at '.self::class);
		}

		$this->loadMigration($migration_name);

		call_user_func([__CLASS__, $method]);

		return "Done.\n";
	}

	/**
	 * Maps a migration command to the appropriate handler within the class
	 *
	 * @param  string $command_name The input command. @see self::COMMAND_LIST
	 * @return string               The handled for the command.
	 */
	protected function getCommandMethod(string $command_name): string
	{
		return 'command'.ucfirst($command_name);
	}

	/**
	 * Validates and loads a migration
	 *
	 * @param  string $migration_name The name of the migration - without path or extension
	 * @return void
	 * @throws RuntimeException When the migration isn't found or does not return a valid instance
	 */
	protected function loadMigration(string $migration_name): void
	{
		$migration_file = self::MIGRATION_PATH . '/' . $migration_name . '.php';

		if (!file_exists($migration_file)) {
			throw new RuntimeException('Migration not found. Searching for '.$migration_file);
		}

		$migration = require $migration_file;

		if (!$migration instanceof Migration) {
			throw new RuntimeException('Migration is invalid. Must return an instance of '.Migration::class);
		}

		$this->migration = $migration;
	}

	/**
	 * Attempts to create the migration in the database
	 *
	 * @return void
	 */
	protected function commandRun(): void
	{
		$this->migration->up();
	}

	/**
	 * Attempts to remove the migration from the database
	 *
	 * @return void
	 */
	protected function commandRollback(): void
	{
		$this->migration->down();
	}

	/**
	 * Refreshes the migration in the database
	 * Warning: This may delete existing data
	 *
	 * @return void
	 */
	protected function commandRefresh(): void
	{
		$this->commandRollback();
		$this->commandRun();
	}
}
