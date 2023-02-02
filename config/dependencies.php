<?php

use Slim\App;
use Slim\Http\Environment;
use Slim\Http\Uri;
use Slim\Views\Twig;
use Slim\Views\TwigExtension;

/**
 * Application dependencies
 */
return function (App $app) {
	$container = $app->getContainer();

	// Register Twig View helper
	$container['view'] = function ($c) {
		$view = new Twig(__DIR__ . '/../resources/views', [
			'cache' => false
		]);

		// Instantiate and add Slim specific extension
		$router = $c->get('router');
		$uri = Uri::createFromEnvironment(new Environment($_SERVER));
		$view->addExtension(new TwigExtension($router, $uri));

		return $view;
	};
};
