<?php

use Slim\App;
use App\Controllers\HomeController;

/**
 * Route definitions
 */

return function (App $app) {

	$app->get('/', HomeController::class . ':home');
};
