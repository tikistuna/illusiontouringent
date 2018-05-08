<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TextError extends Model
{
	protected $fillable = [
		'error',
		'text_record_id',
		];
}
