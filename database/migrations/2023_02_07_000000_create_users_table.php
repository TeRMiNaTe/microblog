<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Capsule\Manager as Capsule;

return new class extends Migration
{
	/**
	 * Create the users table
	 *
	 * @return void
	 */
	public function up()
	{
		Capsule::schema()->create('users', function (Blueprint $table) {
			$table->increments('id');
			$table->string('name');
			$table->string('email')->unique();
			$table->string('password');
			$table->timestamps();
		});
	}

	/**
	 * Drop the users table
	 *
	 * @return void
	 */
	public function down()
	{
		Capsule::schema()->dropIfExists('users');
	}
};
