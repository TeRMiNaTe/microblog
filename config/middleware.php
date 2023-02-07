<?php

use adrianfalleiro\SlimCLIRunner;
use Slim\App;

/**
 * Application Middleware
 */
return function (App $app) {
	/** @see Documentation: https://github.com/adrianfalleiro/slim-cli-runner/tree/slim-3 */
	$app->add(SlimCLIRunner::class);
};
