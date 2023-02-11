<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Capsule\Manager as Capsule;

return new class extends Migration
{
	/**
	 * Create the user roles tables
	 *
	 * @return void
	 */
	public function up()
	{
		Capsule::schema()->create('roles', function (Blueprint $table) {
			$table->id();
			$table->string('name');
		});

		Capsule::schema()->create('user_roles', function (Blueprint $table) {
			$table->integer('id_user');
			$table->integer('id_role');

			$table->unique(['id_user', 'id_role']);
		});
	}

	/**
	 * Drop the user roles tables
	 *
	 * @return void
	 */
	public function down()
	{
		Capsule::schema()->dropIfExists('user_roles');
		Capsule::schema()->dropIfExists('roles');
	}
};
