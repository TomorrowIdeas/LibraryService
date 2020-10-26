<?php

namespace App\Http\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class TimingMiddleware implements MiddlewareInterface
{
	/**
	 * Request timing.
	 *
	 * @param ServerRequestInterface $request
	 * @param RequestHandlerInterface $handler
	 * @return ResponseInterface
	 */
	public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
	{
		$start = \microtime(true);

		$response = $handler->handle($request);

		return $response->withAddedHeader(
			"X-Timing",
			((string) \round((\microtime(true) - $start) * 1000, 2)) . "ms"
		);
	}
}