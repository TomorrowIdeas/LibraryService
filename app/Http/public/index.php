<?php

use Capsule\Factory\ServerRequestFactory;
use Carton\Container;
use Limber\Application;

/**
 * @var Container $container
 */
$container = require __DIR__ . "/../../../bootstrap.php";
$container->register(\config("http.providers"));

/**
 * @var Application $application
 */
$application = $container->get(Application::class);

$response = $application->dispatch(
    ServerRequestFactory::createFromGlobals()
);

$application->send($response);
