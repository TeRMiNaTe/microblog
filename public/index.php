<?php

use Monolog\Logger;
use Slim\App;

/**
 * Main Application Entry Point
 */
require __DIR__ . '/../vendor/autoload.php';

$config = [
    'settings' => [
        'displayErrorDetails' => true,

        'logger' => [
            'name' => 'slim-app',
            'level' => Logger::DEBUG,
            'path' => __DIR__ . '/../logs/app.log',
        ],
    ],
];

$app = new App($config);

// Register routes
$routes = require __DIR__ . '/../routes/web.php';
$routes($app);

$app->run();
