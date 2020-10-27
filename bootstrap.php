<?php

use Caboodle\Config;
use Caboodle\Loaders\FileLoader;
use Carton\Container;
use Dotenv\Dotenv;

require_once __DIR__ . "/vendor/autoload.php";

\defined("APP_ROOT") or \define("APP_ROOT", __DIR__);

if( \file_exists(APP_ROOT . "/.env") ) {
	Dotenv::createUnsafeMutable(APP_ROOT)->safeLoad();
}

$container = Container::getInstance();

$container->set(
	Config::class,
	new Config([
		new FileLoader(__DIR__ . "/config")
	])
);
$container->register(\config('app.providers'));

return $container;