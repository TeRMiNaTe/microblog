<?php

use Slim\App;
use App\Controllers\AuthController;
use App\Controllers\BlogController;

/**
 * Route definitions
 */

return function (App $app) {

	$app->get('/', AuthController::class . ':login')->setName('home');

	$app->get('/register', AuthController::class . ':register')->setName('register');

	$app->get('/blog', BlogController::class . ':list')->setName('blog');

};
