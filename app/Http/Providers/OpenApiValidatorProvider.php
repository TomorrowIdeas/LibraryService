<?php

namespace App\Http\Providers;

use Carton\Container;
use Carton\ServiceProviderInterface;
use League\OpenAPIValidation\PSR7\ValidatorBuilder;

class OpenApiValidatorProvider implements ServiceProviderInterface
{
	public function register(Container $container): void
	{
		$container->singleton(
			ValidatorBuilder::class,
			function(): ValidatorBuilder {
				$validatorBuilder = new ValidatorBuilder;
				$validatorBuilder->fromJson(
					\file_get_contents(\config("http.schema"))
				);

				return $validatorBuilder;
			}
		);
	}
}