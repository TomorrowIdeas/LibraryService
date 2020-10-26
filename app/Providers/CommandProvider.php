<?php

namespace App\Providers;

use Carton\Container;
use Carton\ServiceProviderInterface;

class CommandProvider implements ServiceProviderInterface
{
	public function register(Container $container): void
	{
		foreach( \config("app.commands") as $command ){
			$container->singleton(
				$command,
				function() use ($command) {
					return new $command;
				}
			);
		}
	}
}