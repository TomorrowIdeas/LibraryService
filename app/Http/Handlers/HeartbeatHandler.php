<?php

namespace App\Http\Handlers;

use Capsule\Response;
use Capsule\ResponseStatus;
use Psr\Http\Message\ResponseInterface;

class HeartbeatHandler
{
	/**
	 * Respond to load balancer's heartbeat request.
	 *
	 * @return ResponseInterface
	 */
	public function check(): ResponseInterface
	{
		return new Response(
			ResponseStatus::OK,
			"OK"
		);
	}
}