<?php

use Illuminate\Database\Capsule\Manager;
use Slim\App;

/**
 * Database connection
 */

$capsule = new Manager;

$capsule->addConnection(array_merge_recursive([
	'driver'    => 'mysql',
	'host'      => 'localhost',
	'database'  => 'microblog',
	'charset'   => 'utf8mb4',
	'collation' => 'utf8mb4_general_ci',
	'prefix'    => '',
], $env['database']));

$capsule->setAsGlobal();
$capsule->bootEloquent();

return function (App $app) use ($capsule) {
	$container = $app->getContainer();

	// Service factory for the ORM
	$container['db'] = function ($c) use ($capsule) {
		return $capsule;
	};
};
