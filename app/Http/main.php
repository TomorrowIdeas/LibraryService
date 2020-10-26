<?php

use App\Core\Log;
use React\Http\Server as HttpServer;
use Phinx\Migration\Manager as MigrationManager;

$container = require __DIR__ . "/../../bootstrap.php";

Log::info("• Running migrations");
$container->get(MigrationManager::class)->migrate("default");

Log::info("• Registering HTTP providers");
$container->register(\config("http.providers"));

Log::info("• Listening on " . \config("http.listen"));
$container->get(HttpServer::class)->run();