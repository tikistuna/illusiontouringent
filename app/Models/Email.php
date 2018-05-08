<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Email extends Model
{
	protected $fillable = [
		'name',
		'email',
		'validation_code',
		'status',
	];

	protected $casts = ['status' => 'boolean'];

    public function suscriptions(){
    	return $this->morphToMany('App\Models\City', 'citiable');
    }
}
