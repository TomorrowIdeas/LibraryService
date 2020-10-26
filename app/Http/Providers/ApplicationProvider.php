<?php

namespace App\Http\Providers;


use App\Http\ExceptionHandler;
use Carton\Container;
use Carton\ServiceProviderInterface;
use Limber\Application;
use Limber\Router\Router;

class ApplicationProvider implements ServiceProviderInterface
{
	public function register(Container $container): void
	{
		$container->singleton(
			Application::class,
			function (Container $container): Application {

				$application = new Application(
					$this->createRouter(\config("http.routes"))
				);
				$application->setContainer($container);

				$application->setExceptionHandler(
					[
						new ExceptionHandler(\config("app.debug")),
						"handle"
					]
				);

				$application->setMiddleware(
					\config("http.middleware")
				);
				return $application;
			}
		);
	}

	/**
	 * Create a Router instance hydrated with routes.
	 *
	 * @param array<string> $routes
	 * @return Router
	 */
	private function createRouter(array $routes): Router
	{
		$router = new Router;
		foreach ( $routes as $route ){
			include $route;
		}
		return $router;
	}
}