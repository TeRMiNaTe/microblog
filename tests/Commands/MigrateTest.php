<?php

namespace Tests\Commands;

use App\Commands\BaseCommand;
use App\Commands\Migrate;
use RuntimeException;
use Tests\TestCase;

class MigrateTest extends TestCase
{
	/** @var string Valid migration Command */
	protected const VALID_COMMAND = 'refresh';

	/** @var string Valid migration name */
	protected const VALID_MIGRATION = '2023_02_07_000000_create_users_table';

	/**
	 * Test for a valid command instance
	 *
	 * @return Migrate
	 */
	public function testIsCommandInstance(): Migrate
	{
		$command = new Migrate($this->getAppInstance()->getContainer());

		$this->assertInstanceOf(BaseCommand::class, $command);

		return $command;
	}

	/**
	 * @depends testIsCommandInstance
	 */
	public function testFailsWithInsufficientArguments(Migrate $command): void
	{
		$arguments = [self::VALID_COMMAND];

		$this->expectException(RuntimeException::class);

		$command->command($arguments);
	}

	/**
	 * @depends testIsCommandInstance
	 */
	public function testFailsWithTooManyArguments(Migrate $command): void
	{
		$arguments = [self::VALID_COMMAND, self::VALID_MIGRATION, 'verbose'];

		$this->expectException(RuntimeException::class);

		$command->command($arguments);
	}

	/**
	 * @depends testIsCommandInstance
	 */
	public function testFailsWithInvalidCommand(Migrate $command): void
	{
		$arguments = ['up', self::VALID_MIGRATION];

		$this->expectException(RuntimeException::class);

		$command->command($arguments);
	}

	/**
	 * @depends testIsCommandInstance
	 */
	public function testFailsWithInvalidMigration(Migrate $command): void
	{
		$arguments = [self::VALID_COMMAND, '1000_01_01_000000_i_dont_exist'];

		$this->expectException(RuntimeException::class);

		$command->command($arguments);
	}

	/**
	 * @depends testIsCommandInstance
	 */
	public function testFailsWithInvalidExtension(Migrate $command): void
	{
		$arguments = [self::VALID_COMMAND, self::VALID_MIGRATION.'.php'];

		$this->expectException(RuntimeException::class);

		$command->command($arguments);
	}
}
