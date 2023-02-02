<?php

use Slim\App;

/**
 * Main Application Entry Point
 */
require __DIR__ . '/../vendor/autoload.php';

// Configuration files
$app_config = require __DIR__ . '/../config/app.php';

$app = new App($app_config);

// Register routes
$dependencies = require __DIR__ . '/../config/dependencies.php';
$dependencies($app);

// Register routes
$routes = require __DIR__ . '/../routes/web.php';
$routes($app);

$app->run();
