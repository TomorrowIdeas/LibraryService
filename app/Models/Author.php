<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

/**
 * @property Collection $books
 */
class Author extends BaseModel
{
	public function books(): HasMany
	{
		return $this->hasMany(Book::class);
	}
}