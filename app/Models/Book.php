<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property Author $author
 * @property Publisher $publisher
 */
class Book extends BaseModel
{
	public function author(): BelongsTo
	{
		return $this->belongsTo(Author::class);
	}

	public function publisher(): BelongsTo
	{
		return $this->belongsTo(Publisher::class);
	}
}