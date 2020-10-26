<?php

namespace App\Providers;

use Carton\Container;
use Carton\ServiceProviderInterface;
use Psr\Http\Client\ClientInterface;
use Shuttle\Shuttle;

class HttpClientProvider implements ServiceProviderInterface
{
	public function register(Container $container): void
	{
		$container->singleton(ClientInterface::class,
		function (): ClientInterface{
			return new Shuttle;
		});
	}
}