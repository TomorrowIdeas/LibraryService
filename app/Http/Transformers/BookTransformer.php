<?php

namespace App\Http\Transformers;

use App\Models\Book;
use Remodel\Resource\Resource;
use Remodel\Transformer;

class BookTransformer extends Transformer
{
	public function transform(Book $book): array
	{
		return [
			"id" => $book->id,
			"isbn" => $book->isbn,
			"title" => $book->title,
			"edition" => $book->edition,
			"genre" => $book->genre,
			"summary" => $book->summary,
			"pages" => $book->pages,
			"preview_url" => $book->preview_url,
			"published_at" => $book->published_at,
			"created_at" => $book->created_at,
			"updated_at" => $book->updated_at
		];
	}

	public function author(Book $book): Resource
	{
		return $this->item($book->author, new AuthorTransformer);
	}

	public function publisher(Book $book): Resource
	{
		return $this->item($book->publisher, new PublisherTransformer);
	}
}