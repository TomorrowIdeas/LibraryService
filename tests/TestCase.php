<?php

namespace Tests;

use Illuminate\Support\Facades\DB;
use PDO;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;

abstract class TestCase extends PHPUnitTestCase
{
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
	}
}
