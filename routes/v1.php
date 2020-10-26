<?php

use Limber\Router\Router;

$router->group([
	"prefix" => "v1",
	"namespace" => "App\\Http\\Handlers\\v1"
], function(Router $router): void {

	$router->get("authors", "AuthorsHandler@all");
	$router->get("authors/{id:uuid}", "AuthorsHandler@findById");

});