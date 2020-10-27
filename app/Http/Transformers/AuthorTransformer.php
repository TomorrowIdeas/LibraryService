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
			"website_url" => $author->website_url,
			"created_at" => $author->created_at->format("c"),
			"updated_at" => $author->updated_at->format("c")
		];
	}

	public function books(Author $author): Resource
	{
		return $this->collection($author->books, new BookTransformer);
	}
}