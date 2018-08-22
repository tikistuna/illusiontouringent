<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
	protected $fillable = [ 'name', 'state', 'full_state'];
	protected $hidden = ['created_at', 'updated_at'];
	protected $appends = ['eventsLeft', 'eventsPast'];

	public function events(){
		return $this->hasManyThrough('App\Models\Event', 'App\Models\Venue');
	}

	public function venues(){
		return $this->hasMany('App\Models\Venue');
	}

	public function emails(){
		return $this->morphedByMany('App\Models\Emails', 'citiable');
	}

	public function phones(){
		return $this->morphedByMany('App\Models\Phone', 'citiable');
	}

	public function getEventsLeftAttribute(){
		return $this->events()->upcoming()->get()->count();

	}

	public function getEventsPastAttribute(){
        return $this->events()->past()->get()->count();
    }

}
