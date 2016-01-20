<?php

/**
 * Load bootstrap file
 */
require __DIR__ . '/bootstrap.php';

/**
 * Build new application
 *
 * @var \Example\App
 */
$app = new \Example\App();

/**
 * Run the application and return the response
 */
return $app->run();