<?php

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Slim\App;

/**
 * Application Logger
 */

return function (App $app) use ($env) {
	$container = $app->getContainer();

	$container['logger'] = function ($c) use ($env) {
		$log_path = $env['logger']['path'] ?? __DIR__ . '/logs/app.log';

		$logger = new Logger($log_path);
		$logger->pushHandler(new StreamHandler($log_path, $env['logger']['level'] ?? Logger::DEBUG));

		return $logger;
	};
};
