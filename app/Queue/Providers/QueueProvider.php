<?php

namespace App\Queue\Providers;

use App\Core\Log;
use App\Queue\MessageDeserializer;
use Aws\Sqs\SqsClient;
use Carton\Container;
use Carton\ServiceProviderInterface;
use Predis\Client as RedisClient;
use RuntimeException;
use Syndicate\Queue\MockQueue;
use Syndicate\Queue\Queue;
use Syndicate\Queue\Redis;
use Syndicate\Queue\Sqs;

class QueueProvider implements ServiceProviderInterface
{
	public function register(Container $container): void
	{
		$container->singleton(
			Queue::class,
			function(): Queue {

				$driver = \strtolower(\config("queue.driver"));

				switch( $driver ){

					case "sqs":
						$queue = new Sqs(
							\config("queue.name"),
							new SqsClient([
								"version" => "latest",
								"region" => "us-west-2"
							])
						);
					break;

					case "redis":
						$queue = new Redis(
							\config("queue.name"),
							new RedisClient(\config("queue.host"))
						);
					break;

					case "mock":
						$queue = new MockQueue(
							\config("queue.name")
						);
					break;

					default:
						throw new RuntimeException("Invalid queue driver \"{$driver}\".");
				}

				$queue->setDeserializer(
					[new MessageDeserializer, "deserialize"]
				);

				// Enable asynchronous signal handling
				\pcntl_async_signals(true);

				\pcntl_signal(
					SIGINT,
					function() use ($queue) {
						Log::info("[SIGINT] Shutting queue down gracefully.");
						$queue->shutdown();
					}
				);

				\pcntl_signal(
					SIGHUP,
					function() use ($queue) {
						Log::info("[SIGHUP] Shutting queue down gracefully.");
						$queue->shutdown();
					}
				);

				\pcntl_signal(
					SIGTERM,
					function() use ($queue) {
						Log::info("[SIGTERM] Shutting queue down gracefully.");
						$queue->shutdown();
					}
				);

				return $queue;
			}
		);
	}
}