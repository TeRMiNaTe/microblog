<?php

use Slim\App;

/**
 * List of application services
 */
$services = [
	'session' => \App\Services\SessionService::class,
	'hasher'  => \App\Services\HashService::class,
	'auth'    => \App\Services\AuthService::class,
];

/**
 * Load the services into the application container
 * Each service is assigned the Application container as well as any specific config
 */
return function (App $app) use ($services, $env) {
	$container = $app->getContainer();

	foreach ($services as $key => $service) {
		$container[$key] = function ($c) use ($service, $key, $env) {
			return new $service($c, $env[$key] ?? null);
		};
	}
};
