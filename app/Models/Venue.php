<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Venue extends Model
{
    protected $fillable = [
    	'name',
        'address',
        'website',
        'phone',
        'hours'
    ];

    protected $appends = ['cityName'];
	protected $hidden = ['created_at', 'updated_at'];

    protected $base_url = 'https://www.google.com/maps/embed/v1/place?';

    public function events(){
    	return $this->hasMany('App\Models\Event');
    }

    public function city(){
    	return $this->belongsTo('App\Models\City');
    }

	public function get_base_url(){
    	return $this->base_url;
	}

	public function google_maps(){
    	$venue = str_replace(' ', '+', $this->name);
    	$city = str_replace(' ', '+', $this->city->name);
    	return $this->base_url . "key=" . env('GOOGLE_MAPS_API_KEY', 'default') . "&q=" . $venue . "," . $city . "+" . $this->city->state . "&attribution_source=https://illusiontouringent.com&attribution_ios_deep_link_id=comgooglemaps://?daddr=" . $venue . ", +" . $city . "+" . $this->city->state;
    }

    public function getCityNameAttribute(){
        return $this->city->name;
    }
}
