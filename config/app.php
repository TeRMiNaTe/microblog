<?php

use Monolog\Logger;

/**
 * Application configuration
 */
return [
	'settings' => [
		'displayErrorDetails' => true,

		'logger' => [
			'name' => 'slim-app',
			'level' => Logger::DEBUG,
			'path' => __DIR__ . '/../logs/app.log',
		],
	],
];
