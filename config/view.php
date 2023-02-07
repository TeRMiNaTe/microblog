<?php

use Slim\App;
use Slim\Http\Environment;
use Slim\Http\Uri;
use Slim\Views\Twig;
use Slim\Views\TwigExtension;

/**
 * Application views
 */
return function (App $app) use ($env) {
	$container = $app->getContainer();

	// Register Twig View helper
	$container['view'] = function ($c) use ($env) {
		$template_path = $env['view']['template_path'] ?? __DIR__ . '/../resources/views';

		$view = new Twig($template_path, array_merge_recursive([
			// Global Twig configuration:
			// 'debug' => false,
		], $env['view']['twig']));

		// Instantiate and add Slim specific extension
		$router = $c->get('router');
		$uri = Uri::createFromEnvironment(new Environment($_SERVER));
		$view->addExtension(new TwigExtension($router, $uri));

		return $view;
	};
};
