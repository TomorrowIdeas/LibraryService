<?php

namespace App\Http\Providers;

use App\Core\Log;
use Carton\Container;
use Carton\ServiceProviderInterface;
use Limber\Application;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use React\EventLoop\Factory;
use React\EventLoop\LoopInterface;
use React\Http\Server as HttpServer;
use React\Socket\Server as SocketServer;

class HttpServerProvider implements ServiceProviderInterface
{
	public function register(Container $container): void
	{
		$container->singleton(
			HttpServer::class,
			function(Container $container): LoopInterface {

				$eventLoop = $this->makeEventLoop();

				/**
				 * Get the Application instance from the container.
				 *
				 * @var Application $application
				 */
				$application = $container->get(Application::class);

				$httpServer = new HttpServer(
					$eventLoop,
					function(ServerRequestInterface $request) use ($application): ResponseInterface {
						return $application->dispatch($request);
					}
				);

				$httpServer->listen(
					new SocketServer(
						\config("http.listen"),
						$eventLoop
					)
				);

				return $eventLoop;
			}
		);
	}

	/**
	 * Make the event loop interface instance.
	 *
	 * @return LoopInterface
	 */
	private function makeEventLoop(): LoopInterface
	{
		// Create the event loop
		$eventLoop = Factory::create();

		// Add signal handlers
		$eventLoop->addSignal(
			SIGINT,
			function() use ($eventLoop) {
				Log::info("[SIGINT] Shutting down service.");
				$eventLoop->stop();
			}
		);

		$eventLoop->addSignal(
			SIGTERM,
			function() use ($eventLoop) {
				Log::info("[SIGTERM] Shutting down service.");
				$eventLoop->stop();
			}
		);

		$eventLoop->addSignal(
			SIGHUP,
			function() use ($eventLoop) {
				Log::info("[SIGHUP] Shutting down service.");
				$eventLoop->stop();
			}
		);

		return $eventLoop;
	}
}