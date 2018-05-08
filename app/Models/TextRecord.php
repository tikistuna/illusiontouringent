<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TextRecord extends Model
{
    protected $fillable = [
    	'text_record_id',
	    'code',
	    'status',
	    'credits',
	    'phone_number',
	    'phone_id',
	    'cost',
	    'action'
    ];

    public function text_errors(){
    	return $this->hasMany('App\Models\TextError');
    }
}
