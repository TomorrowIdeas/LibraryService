<?php

use App\Core\Log;
use GO\Scheduler;

$container = require __DIR__ . "/../../bootstrap.php";

Log::info("• Registering Scheduler providers");
$container->register(
	\config("scheduler.providers")
);

Log::info("• Running scheduled tasks");
try {
	$container->get(Scheduler::class)->run(
		new DateTime($argv[1] ?? "now")
	);
}
catch( Throwable $exception ){
	Log::critical("[SCHEDULER] " . $exception->getMessage());
    \report($exception);
}
