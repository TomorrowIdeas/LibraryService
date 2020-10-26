<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

abstract class BaseModel extends Model
{
	protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;
}