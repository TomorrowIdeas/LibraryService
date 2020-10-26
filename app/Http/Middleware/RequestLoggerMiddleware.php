<?php

namespace App\Http\Middleware;

use App\Core\Log;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class RequestLoggerMiddleware implements MiddlewareInterface
{
	/**
	 * Log all incoming requests and outgoing responses.
	 *
	 * @param ServerRequestInterface $request
	 * @param RequestHandlerInterface $handler
	 * @return ResponseInterface
	 */
	public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
	{
		/**
		 * !!!!!!!
		 *
		 * Do not remove this - we should NEVER be logging request and response data in production.
		 */
		if( \is_env("production") ) {
			return $handler->handle($request);
		}
		/**
		 * !!!!!!!
		 */

		// Build a unique request ID to associate matching request and responses
		$requestId = \bin2hex(\random_bytes(4));

		$endpoint = "{$request->getMethod()}#{$request->getUri()->getPath()}";
		if( ($queryString = $request->getUri()->getQuery()) ){
			$endpoint.="?{$queryString}";
		}

		// Build the request log message
		$message = "[REQUEST] ({$requestId}) => {$endpoint}";

		// Log the request message
		Log::debug($message, [
			"method" => $request->getMethod(),
			"uri" => (string) $request->getUri(),
			"ip" => $request->getServerParams()["REMOTE_ADDR"] ?? "",
			"host" => $request->getUri()->getHost(),
			"user-agent" => $request->getHeaderLine("User-Agent"),
			"headers" => $request->getHeaders(),
			"query" => $request->getQueryParams(),
			"body" => (array) $request->getParsedBody()
		]);

		$response = $handler->handle($request);

		// Build the response log message
		$message = "[RESPONSE] ({$requestId}) => [{$response->getStatusCode()} {$response->getReasonPhrase()}] {$endpoint}";

		// Clone the body so we can dump the response into the log.
		$body = clone $response->getBody();

		Log::debug($message, ["body" => $body->getContents(), "headers" => $response->getHeaders()]);

		return $response;
	}
}