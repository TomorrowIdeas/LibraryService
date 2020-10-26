<?php

namespace App\Http\Transformers;

use App\Models\Author;
use Remodel\Resource\Resource;
use Remodel\Transformer;

class AuthorTransformer extends Transformer
{
	public function transform(Author $author): array
	{
		return [
			"id" => $author->id,
			"name" => $author->name,
			"created_at" => $author->created_at,
			"updated_at" => $author->updated_at
		];
	}

	public function books(Author $author): Resource
	{
		return $this->collection($author->books, new BookTransformer);
	}
}