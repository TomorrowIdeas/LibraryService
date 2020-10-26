<?php

namespace App\Http\Middleware;

use Limber\Exceptions\NotAcceptableHttpException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ParseBodyMiddleware implements MiddlewareInterface
{
	public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
	{
		if( \in_array($request->getMethod(), ["POST", "PUT", "PATCH"]) &&
			$request->getParsedBody() == false &&
			$request->getBody()->getSize() ){

			$contentType = $request->getHeaderLine("Content-Type");

			$body = clone $request->getBody();

			if( \stripos($contentType, "application/json") !== false ){
				$parsedBody = \json_decode($body->getContents()) ?? false;
			}
			else {
				throw new NotAcceptableHttpException("{$contentType} is not supported.");
			}

			if( $parsedBody !== false ){
				$request = $request->withParsedBody($parsedBody);
			}
		}

		return $handler->handle($request);
	}
}