<?php

use Slim\App;

/**
 * Application Bootstrap
 */
require __DIR__ . '/../vendor/autoload.php';

$env = require __DIR__ . '/../env.php';

$app = new App([
	'settings' => require __DIR__ . '/../config/app.php',
	'commands' => require __DIR__ . '/../config/commands.php',
]);

// Register logger
$logger = require __DIR__ . '/../config/logger.php';
$logger($app);

// Register DB ORM
$database = require __DIR__ . '/../config/database.php';
$database($app);

// Register view processor
$view = require __DIR__ . '/../config/view.php';
$view($app);

// Register Middleware
$middleware = require __DIR__ . '/../config/middleware.php';
$middleware($app);

// Register routes
$routes = require __DIR__ . '/../routes/web.php';
$routes($app);
