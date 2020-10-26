<?php

return [

	/**
	 * The Queue driver to use.
	 *
	 * Supports:
	 *  - sqs
	 *  - redis
	 *  - mock
	 */
	"driver" => \getenv("QUEUE_DRIVER"),

	/**
	 * The Queue name to listen on.
	 */
	"name" => \getenv("QUEUE_NAME"),

	/**
	 * The hostname of the queue (only used by Redis queue driver.)
	 */
	"host" => \getenv("QUEUE_HOST"),

	/**
	 * Queue polling timeout in seconds.
	 */
	"timeout" => 5,

	/**
	 * Queue specific providers.
	 */
	"providers" => [
		App\Queue\Providers\DispatcherProvider::class,
		App\Queue\Providers\QueueProvider::class
	],

	/**
	 * List of routes.
	 */
	"routes" => [
		"SampleCreatedEvent" => "\App\Queue\Handlers\SampleHandler@onCreated"
	]
];