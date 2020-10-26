<?php

namespace App\Http\Middleware;

use League\OpenAPIValidation\PSR7\Exception\NoPath;
use League\OpenAPIValidation\PSR7\Exception\ValidationFailed;
use League\OpenAPIValidation\PSR7\ValidatorBuilder;
use Limber\Exceptions\BadRequestHttpException;
use Limber\Exceptions\NotFoundHttpException;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class RequestValidatorMiddleware implements MiddlewareInterface
{
	public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
	{
		if( \in_array($request->getMethod(), ["POST", "PUT", "PATCH"]) ){

			/**
			 * @var ValidatorBuilder $validator
			 */
			$validator = \container(ValidatorBuilder::class);

			try {

				$validator->getServerRequestValidator()->validate($request);
			}
			catch( NoPath $noPathException ){
				throw new NotFoundHttpException($noPathException->getMessage());
			}
			catch( ValidationFailed $validationFailed ){
				throw new BadRequestHttpException($validationFailed->getMessage());
			}
		}

		return $handler->handle($request);
	}
}