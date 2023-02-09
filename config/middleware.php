<?php

use Slim\App;

/**
 * Application Middleware
 */
return function (App $app) {
	/** @see Documentation: https://github.com/adrianfalleiro/slim-cli-runner/tree/slim-3 */
	$app->add(\adrianfalleiro\SlimCLIRunner::class);

	$app->add(\App\Middleware\RedirectExceptionHandler::class);
};
