<?php

namespace App\Http\Handlers\v1;

use App\Http\JsonResponse;
use App\Http\JsonSerializer;
use App\Http\Transformers\AuthorTransformer;
use App\Models\Author;
use Capsule\ResponseStatus;
use Limber\Exceptions\NotFoundHttpException;
use Psr\Http\Message\ServerRequestInterface;
use Remodel\Resource\Collection;
use Remodel\Resource\Item;

class AuthorsHandler
{
	public function all(ServerRequestInterface $request): JsonResponse
	{
		$page = $request->getQueryParams()["p"] ?? 1;
		$limit = $request->getQueryParams()["limit"] ?? 25;

		$authors = Author::orderBy("name")
		->offset(($page - 1) * $limit)
		->limit($limit)
		->get();

		return new JsonResponse(
			ResponseStatus::OK,
			new JsonSerializer(
				new Collection($authors, new AuthorTransformer)
			)
		);
	}

	public function findById(string $id): JsonResponse
	{
		$author = Author::find($id);

		if( empty($author) ){
			throw new NotFoundHttpException("Author not found");
		}

		return new JsonResponse(
			ResponseStatus::OK,
			new JsonSerializer(
				new Item($author, (new AuthorTransformer)->setIncludes(["books", "books.publisher"]))
			)
		);
	}
}