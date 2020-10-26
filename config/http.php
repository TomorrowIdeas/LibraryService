<?php

return [

	/**
	 * Listen on ip:port.
	 */
	"listen" => "0.0.0.0:8000",

	/**
	 * Open API schema file.
	 */
	"schema" => APP_ROOT . "/openapi.json",

	/**
	 * Global application middleware.
	 */
	"middleware" => [
		App\Http\Middleware\ParseBodyMiddleware::class,
		App\Http\Middleware\RequestLoggerMiddleware::class,
		App\Http\Middleware\ServerHeaderMiddleware::class,
		App\Http\Middleware\TimingMiddleware::class,
		App\Http\Middleware\RequestValidatorMiddleware::class
	],

	/**
	 * HTTP specific application providers.
	 */
	"providers" => [
		//App\Http\Providers\OpenApiValidatorProvider::class,
		App\Http\Providers\ApplicationProvider::class,
		App\Http\Providers\HttpServerProvider::class
	],

	/**
	 * HTTP API routes.
	 */
	"routes" => [
		APP_ROOT . "/routes/global.php",
		APP_ROOT . "/routes/v1.php"
	]
];