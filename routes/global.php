<?php

use Limber\Router\Router;

/**
 * @var Router $router
 */
$router->group([
	"namespace" => "App\Http\Handlers"
], function (Router $router){

	$router->get("/heartbeat", "HeartbeatHandler@check");

});