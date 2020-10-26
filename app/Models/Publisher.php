<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property Collection $books
 */
class Publisher extends BaseModel
{
	public function books(): HasMany
	{
		return $this->hasMany(Book::class);
	}
}