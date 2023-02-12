<?php

use App\Controllers\AuthController;
use App\Controllers\BlogController;
use App\Controllers\PostController;
use App\Controllers\RoleController;
use App\Middleware\AuthenticatedMiddleware;
use App\Middleware\GuestMiddleware;
use App\Middleware\PublisherMiddleware;
use App\Middleware\RedirectExceptionListener;
use Slim\App;

/**
 * Route definitions
 */

return function (App $app) {

	$app->get('/', AuthController::class . ':login')->setName('home');
	$app->get('/register', AuthController::class . ':register')->setName('register')->add(GuestMiddleware::class);

	$app->group('/auth', function() {
		$this->post('/register', AuthController::class . ':handleRegister')->setName('auth-register')->add(GuestMiddleware::class);
		$this->post('/login', AuthController::class . ':handleLogin')->setName('auth-login')->add(GuestMiddleware::class);
		$this->get('/logout', AuthController::class . ':handleLogout')->setName('auth-logout')->add(AuthenticatedMiddleware::class);

		$this->post('/unregister', AuthController::class . ':handleUnregister')->setName('auth-unregister')->add(AuthenticatedMiddleware::class);
	})->add(RedirectExceptionListener::class);

	$app->group('/blog', function() {
		$this->get('/', BlogController::class . ':list')->setName('blog');
		$this->get('/publish', BlogController::class . ':publish')->setName('publish')->add(AuthenticatedMiddleware::class);
		$this->get('/edit/{id}', BlogController::class . ':edit')->setName('edit')->add(PublisherMiddleware::class);
	});

	$app->group('/post', function() {
		$this->post('/create', PostController::class . ':create')->setName('post-create');
		$this->post('/update', PostController::class . ':update')->setName('post-update');
		$this->post('/delete', PostController::class . ':delete')->setName('post-delete');
	})->add(PublisherMiddleware::class)->add(RedirectExceptionListener::class);

	$app->post('/roles/{name}/manage', RoleController::class . ':manage')->setName('role-manage')->add(RedirectExceptionListener::class)->add(AuthenticatedMiddleware::class);
};
