<?php

use Carton\Container;
use Illuminate\Support\Facades\DB;
use Phinx\Config\Config;
use Phinx\Migration\Manager as MigrationManager;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\NullOutput;

/**
 * @var Container $container
 */
$container = require __DIR__ . "/../bootstrap.php";

$manager = new MigrationManager(
	new Config([
		"paths" => [
			"migrations" => APP_ROOT . "/database/migrations/"
		],
		"environments" => [
			"default_migration_table" => "migrations",
			"test" => [
				"adapter" => "sqlite",
				"database" => ":memory:",
				"connection" => $container->get(DB::class)->getConnection()->getPdo()
			]
		]
	]),
	new StringInput(" "),
	new NullOutput()
);
$manager->migrate("test");

// Replace migration manager with this instance instead.
$container->set(MigrationManager::class, $manager);