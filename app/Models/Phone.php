<?php

namespace App\Models;

use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Phone extends Model
{

	use Notifiable;

	protected $fillable = [
		'name',
		'phone',
		'validation_code',
		'status',
		'blacklisted'
	];

	protected $casts = ['status' => 'boolean', 'blacklisted' => 'boolean'];

    public function suscriptions(){
		return $this->morphToMany('App\Models\City', 'citiable')->withTimestamps();
    }

    public static function getDigits($phone){
    	$digits =  preg_replace('/[^0-9]/', '', $phone);
    	if(((int)substr($digits, 0, 1)) === 1){
    		return $digits;
	    }
	    return '1' . $digits;
    }

	public function phoneForHumans(){
		$prefix = substr($this->phone, 0, 3);
		$phone = substr($this->phone, 3);
		$phone = substr($phone, 0, 3) . "-" . substr($phone, 3);
		return "({$prefix}){$phone}";
	}
}
