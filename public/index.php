<?php

require __DIR__ . '/../bootstrap/app.php';

/**
 * Main Application Entry Point
 */

$app->getContainer()->get('session')->start();

$app->run();
