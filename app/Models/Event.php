<?php

namespace App\Models;

use App\Contracts\UrlShortener\UrlShortener;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
		'name',
	    'date',
	    'description',
	    'venue_id',
	    'reminder_description',
	    'illusion',
	    'active'
    ];

	protected $casts = ['two_week_reminder_sent' => 'boolean', 'one_week_reminder_sent' => 'boolean', 'active' => 'boolean'];
    protected $dates = ['date'];
    protected $appends = ['dateFormatted', 'cityName', 'venueName', 'pricesAsString'];
    protected $hidden = ['created_at', 'updated_at', 'city_id', 'venue_id'];

    public function city(){
    	return $this->venue->city();
    }

	public function venue(){
		return $this->belongsTo('App\Models\Venue');
	}

	public function photo(){
    	return $this->hasOne('App\Models\Photo');
	}

	public function prices(){
    	return $this->hasMany('App\Models\Price');
	}

	public function ticket_sellers(){
    	return $this->belongsToMany('App\Models\TicketSeller')->withPivot('website');
	}

	public function ticketSellersWithShortUrl(){
		return $this->belongsToMany('App\Models\TicketSeller')->withPivot('website')->wherePivot('website', 'LIKE', 'https://goo.gl%');
	}

	public function getPricesAsStringAttribute(){
    	return implode(', ', $this->prices()->orderBy('price')->pluck('price')->toArray());
	}

	public function getDateFormatted(){
    	if($this->date->gt(Carbon::now('America/Chicago'))){
		    $date = $this->date->formatLocalized('%A, %b %e @ %l:%M');
		    $date .= 'PM';
		    return ucwords($date);
	    }else{
		    $date = $this->date->formatLocalized('%A, %b %e, %Y');
    		return ucwords($date);
	    }

    }

    public function getDateFormattedAttribute(){
	    $date = $this->date->formatLocalized('%A, %b %e, %Y');
	    return ucwords($date);
    }

    public function getCityNameAttribute(){
    	return $this->city->name;
    }

	public function getVenueNameAttribute(){
		return $this->venue->name;
	}

	public function getTextMessageAttribute(){
    	if(is_null($this->reminder_description)){
    		return [];
	    }
    	$msg = explode(' ', $this->reminder_description, 4);
    	[$event, $date, $venue, $description] = $msg;
    	return compact('event', 'date', 'venue', 'description');
	}

	/**
	 * Scopes for Model
	 */

	public function scopeUpcoming($query){
		return $query->whereDate('date', '>=', Carbon::today()->toDateString());
	}

	public function scopePast($query){
		return $query->whereDate('date', '<', Carbon::today()->toDateString());
	}

	public function scopePublic($query){
		return $query->where('active', 1);
	}

	public function updatePrices($prices){

		$toBeRemoved = $this->prices()->pluck('price')->diff($prices);
		if($toBeRemoved->count() > 0){
			Price::where('event_id', $this->id)->whereIn('price', $toBeRemoved)->delete();
		}

		$toBeAdded = $prices->diff($this->prices()->pluck('price'));
		foreach($toBeAdded as $price){
			$this->prices()->create(['price'=> $price]);
		}
	}

	public function firstLetterOfDay(){
		$day = $this->date->dayOfWeek;
		$days = ['L', 'Ma', 'Mi', 'J', 'V', 'S', 'D'];
		return $days[$day];
	}
}
