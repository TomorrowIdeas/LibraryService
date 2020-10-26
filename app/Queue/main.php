<?php

use App\Core\Log;
use Phinx\Migration\Manager as MigrationManager;
use Syndicate\Dispatcher;
use Syndicate\Message;
use Syndicate\Queue\Queue;

$container = require __DIR__ . "/../../bootstrap.php";

Log::info("• Running migrations");
$container->get(MigrationManager::class)->migrate("default");

Log::info("• Registering Queue providers");
$container->register(\config("queue.providers"));

Log::info("• Starting consumer");

/**
 * @var Queue $queue
 */
$queue = $container->get(Queue::class);

/**
 * @var Dispatcher $dispatcher
 */
$dispatcher = $container->get(Dispatcher::class);

$queue->listen(function(Message $message) use ($dispatcher): void {

	$dispatcher->dispatch($message);

}, \config("queue.timeout"));