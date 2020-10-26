<?php

namespace App\Core;

use Carton\NotFoundException;
use Monolog\Logger;
use function call_user_func_array;
use function container;

/**
 * @method static void debug(string $message, array $context = [])
 * @method static void info(string $message, array $context = [])
 * @method static void notice(string $message, array $context = [])
 * @method static void warning(string $message, array $context = [])
 * @method static void error(string $message, array $context = [])
 * @method static void critical(string $message, array $context = [])
 * @method static void alert(string $message, array $context = [])
 * @method static void emergency(string $message, array $context = [])
 */
class Log
{
	/**
	 * Call instance method on Logger.
	 *
	 * @param string $method
	 * @param array $params
	 * @throws NotFoundException
	 * @return mixed
	 */
	public static function __callStatic(string $method, array $params)
	{
		return call_user_func_array(
			[container(Logger::class), $method],
			$params
		);
	}
}