<?php

namespace App\Http\Transformers;

use App\Models\Publisher;
use Remodel\Resource\Resource;
use Remodel\Transformer;

class PublisherTransformer extends Transformer
{
	public function transform(Publisher $publisher): array
	{
		return [
			"id" => $publisher->id,
			"name" => $publisher->name,
			"created_at" => $publisher->created_at,
			"updated_at" => $publisher->updated_at
		];
	}

	public function books(Publisher $publisher): Resource
	{
		return $this->collection($publisher->books, new BookTransformer);
	}
}