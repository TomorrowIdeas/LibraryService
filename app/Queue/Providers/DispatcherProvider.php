<?php

namespace App\Queue\Providers;

use App\Core\Log;
use Carton\Container;
use Carton\ServiceProviderInterface;
use Syndicate\Dispatcher;
use Syndicate\Message;
use Syndicate\Router;

/**
 *
 * Build the application"s Dispatcher instance.
 *
 */
class DispatcherProvider implements ServiceProviderInterface
{
	public function register(Container $container): void
	{
		$container->singleton(
			Dispatcher::class,
			function(Container $container){

				$dispatcher = new Dispatcher(
					$this->createRouter(\config("queue.routes")),
					$container
				);

				$dispatcher->setDefaultHandler(
					function(Message $message): void {

						$message->delete();
						Log::critical(
								"No route found for message. Message being deleted.",
								["message" => $message->getSourceMessage()]
						);

					}
				);

				return $dispatcher;
			}
		);
	}

	private function createRouter(array $routes): Router
	{
		return new Router(
			function(Message $message, string $route): bool {

				if( empty($message->getPayload()) ){
					return false;
				}

				return $message->getPayload()->getName() === $route;
			},
			$routes
		);
	}
}