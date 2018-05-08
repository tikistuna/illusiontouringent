<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketSeller extends Model
{
    protected $fillable = [
    	'name',
	    'phone',
	    'address',
	    'website',
	    'hours',
    ];

	protected $base_url = 'https://www.google.com/maps/embed/v1/place?';

	public function google_maps($city){
		$venue = str_replace(' ', '+', $this->name);
		$address = str_replace(' ', '+', $this->address);
		if($this->id === 7){
			$venue = $address;
		}
		$city_name = str_replace(' ', '+', $city->name);

		return $this->base_url . "key=" . env('GOOGLE_MAPS_API_KEY', 'default') . "&q=" . $venue . "," . $city_name . "+" . $city->state . "&attribution_source=https://ljconciertos.com&attribution_ios_deep_link_id=comgooglemaps://?daddr=" . $address;
	}

	public function events(){
		return $this->belongsToMany('App\Models\Event');
	}
}
