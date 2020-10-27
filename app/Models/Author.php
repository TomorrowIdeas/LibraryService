<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

/**
 * @property string $id
 * @property string $name
 * @property string|null $website_url
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property Collection $books
 */
class Author extends BaseModel
{
	public function books(): HasMany
	{
		return $this->hasMany(Book::class);
	}
}