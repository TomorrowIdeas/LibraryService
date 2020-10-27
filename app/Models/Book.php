<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string $id
 * @property string $isbn
 * @property string $author_id
 * @property string $publisher_id
 * @property string $title
 * @property int|null $edition
 * @property string $genre
 * @property string $summary
 * @property int $pages
 * @property string|null $preview_url
 * @property string	$published_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
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