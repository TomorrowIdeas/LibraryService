<?php

namespace Tests;

use Capsule\ServerRequest;
use Illuminate\Support\Facades\DB;
use Limber\Application;
use PDO;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;
use Psr\Http\Message\ResponseInterface;

abstract class TestCase extends PHPUnitTestCase
{
	/**
	 * Application instance.
	 *
	 * @var Application
	 */
	protected $application;

	/**
	 * Setup the test case.
	 *
	 * @return void
	 */
	public function setUp(): void
	{
		/**
		 * Start with clean database for each test.
		 *
		 * @var PDO $pdo
		 */
		$pdo = \container(DB::class)->getConnection()->getPdo();

		$tables = $pdo->query("select name from sqlite_master where type='table' order by name", PDO::FETCH_OBJ);

		foreach($tables as $table) {
			if( $table->name !== "migrations" ) {
				$pdo->exec("delete from {$table->name}");
			}
		}

		$this->application = \container(Application::class);
	}

	/**
	 * Make an HTTP request to the application.
	 *
	 * @param string $method
	 * @param string $uri
	 * @param array|null $body
	 * @param array $headers
	 * @return ResponseInterface
	 */
	protected function makeRequest(string $method, string $uri, ?array $body = null, array $headers = []): ResponseInterface
	{
		return $this->application->dispatch(
			new ServerRequest(
				$method,
				$uri,
				$body ? \json_encode($body) : null,
				\array_merge(
					[
						"Content-Type" => "application/json"
					],
					$headers
				)
			)
		);
	}
}
