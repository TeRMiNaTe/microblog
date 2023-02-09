<?php

use App\Controllers\AuthController;
use App\Controllers\BlogController;
use App\Middleware\RedirectExceptionListener;
use Slim\App;

/**
 * Route definitions
 */

return function (App $app) {

	$app->get('/', AuthController::class . ':login')->setName('home');
	$app->get('/register', AuthController::class . ':register')->setName('register');

	$app->group('/auth', function() {
		$this->post('/register', AuthController::class . ':handleRegister')->setName('auth-register');
		$this->post('/login', AuthController::class . ':handleLogin')->setName('auth-login');
		$this->get('/logout', AuthController::class . ':handleLogout')->setName('auth-logout');
	})->add(RedirectExceptionListener::class);

	$app->get('/blog', BlogController::class . ':list')->setName('blog');

};
