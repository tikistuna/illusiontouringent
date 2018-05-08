<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
	protected $fillable = [ 'name', 'state'];
	protected $hidden = ['created_at', 'updated_at'];
	protected $appends = ['eventsLeft', 'eventsPast'];

	public function events(){
		return $this->hasMany('App\Models\Event');
	}

	public function venues(){
		return $this->hasManyThrough('App\Models\Venue', 'App\Models\Event');
	}

	public function emails(){
		return $this->morphedByMany('App\Models\Emails', 'citiable');
	}

	public function phones(){
		return $this->morphedByMany('App\Models\Phone', 'citiable');
	}

	public function getEventsLeftAttribute(){
		return $this->withCount(['events' => function($query){
		    $query->whereDate('date', '>=', Carbon::today()->toDateString());
        }])->find($this->id)->events_count;

	}

	public function getEventsPastAttribute(){
        return $this->withCount(['events' => function($query){
            $query->whereDate('date', '<', Carbon::today()->toDateString());
        }])->find($this->id)->events_count;
    }

}
