<?php

require __DIR__ . '/Application.php';
require __DIR__ . '/../../vendor/autoload.php';

use Examples\Basic\Application;

$app = new Application();

$routesFile = implode(DIRECTORY_SEPARATOR, [__DIR__, 'routes.php']);

if (file_exists($routesFile)) {
    require $routesFile;
}

$app->run();