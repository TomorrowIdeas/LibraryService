<?php

use Airbrake\Instance as Airbrake;
use Caboodle\Config;
use Carton\Container;

if (!function_exists("container")) {

	/**
	 * Get an instance from container.
	 *
	 * @param string $key
	 * @return object
	 */
	function container(string $key): object
	{
		return Container::getInstance()->get($key);
	}
}

if (!function_exists("config")) {

	/**
	 * Get a value from a config.
	 *
	 * @param string $key
	 * @return mixed
	 */
	function config(string $key)
	{
		return \container(Config::class)->get($key);
	}
}

if (!function_exists("is_env")) {

	/**
	 * Check current application environment.
	 *
	 * @param string|array $environment
	 * @return boolean
	 */
	function is_env($environment): bool
	{
		if (!is_array($environment)) {
			$environment = [$environment];
		}

		return in_array(
			\config("app.env"),
			$environment
		);
	}
}

if (!\function_exists("report")) {

	/**
	 * Report an exception to Airbrake.
	 *
	 * @param Throwable $throwable
	 * @return void
	 */
	function report(Throwable $throwable): void
	{
		if (\config("app.reporting")) {
			Airbrake::notify($throwable);
		}
	}
}