<?php

namespace App\Providers;

use Carton\Container;
use Carton\ServiceProviderInterface;
use Phinx\Config\Config;
use Phinx\Migration\Manager as MigrationManager;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\NullOutput;

class MigrationsProvider implements ServiceProviderInterface
{
	/**
	 * @inheritDoc
	 */
	public function register(Container $container): void
	{
		$container->singleton(
			MigrationManager::class,
			function (): MigrationManager {
				return new MigrationManager(
					new Config([
						"paths" => [
							"migrations" => APP_ROOT . "/database/migrations"
						],
						"environments" => [
							"default_migration_table" => "migrations",
							"default" => [
								"adapter" => \config("database.connections.default.driver"),
								"host" => \config("database.connections.default.host"),
								"name" => \config("database.connections.default.database"),
								"user" => \config("database.connections.default.username"),
								"pass" => \config("database.connections.default.password"),
								"port" => \config("database.connections.default.port"),
								"charset" => \config("database.connections.default.charset"),
								"suffix" => ""
							]
						]
					]),
					new StringInput(""),
					new NullOutput()
				);
			}
		);
	}
}