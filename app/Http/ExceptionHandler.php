<?php

namespace App\Http;

use App\Core\Log;
use App\Http\JsonResponse;
use Capsule\ResponseStatus;
use Limber\Exceptions\HttpException;
use Psr\Http\Message\ResponseInterface;
use Throwable;

class ExceptionHandler
{
	/**
	 * Include debug messages in response.
	 *
	 * @var bool
	 */
	protected $debug;

	public function __construct(bool $debug = false)
	{
		$this->debug = $debug;
	}

	public function handle(Throwable $throwable): ResponseInterface
	{
		$statusCode = $this->getStatusCode($throwable);

		if ( $statusCode >= ResponseStatus::INTERNAL_SERVER_ERROR ) {
			Log::critical($throwable->getMessage(), [
				"file" => $throwable->getFile(),
				"line" => $throwable->getLine()
			]);
		}

		$error = [
			"code" => $throwable->getCode(),
			"message" => $statusCode < 500 ? $throwable->getMessage() : "Internal Server Error!"
		];

		if( $this->debug ) {
			$errorBody = [
				"http" => [
					"status" => $statusCode,
					"reason" => ResponseStatus::getPhrase($statusCode)
				],

				"debug" => [
					"code" => $throwable->getCode(),
					"message" => $throwable->getMessage(),
					"file" => $throwable->getFile(),
					"line" => $throwable->getLine(),
					"trace" => $throwable->getTraceAsString()
				]
			];

			$error = \array_merge($error, $errorBody);
		}

		$response = new JsonResponse($statusCode, $error);

		// If exception has additional headers that need to be sent, add them to response.
		if( $throwable instanceof HttpException &&
			$throwable->getHeaders() ) {
			foreach( $throwable->getHeaders() as $header => $value ){
				$response = $response->withAddedHeader($header, $value);
			}
		}

		return $response;
	}

	/**
	 * Get the HTTP status code to use for this exception type.
	 *
	 * @param Throwable $exception
	 * @return integer
	 */
	protected function getStatusCode(Throwable $exception): int
	{
		if( $exception instanceof HttpException ){
			return (int) $exception->getHttpStatus();
		}

		return ResponseStatus::INTERNAL_SERVER_ERROR;
	}
}