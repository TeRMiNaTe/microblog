<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Capsule\Manager as Capsule;

return new class extends Migration
{
	/**
	 * Create the posts table
	 *
	 * @return void
	 */
	public function up()
	{
		Capsule::schema()->create('posts', function (Blueprint $table) {
			$table->increments('id');
			$table->string('title');
			$table->text('content');
			$table->string('featured_image');
			$table->unsignedInteger('id_author')->nullable();
			$table->timestamps();
		});
	}

	/**
	 * Drop the posts table
	 *
	 * @return void
	 */
	public function down()
	{
		Capsule::schema()->dropIfExists('posts');
	}
};
