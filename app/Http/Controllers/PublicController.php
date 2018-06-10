<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Event;
use Carbon\Carbon;


class PublicController extends Controller
{
    public function index(){

    	$events = Event::upcoming()->orderBy('date')->offset(0)->limit(8)->get();
	    $cities = City::orderBy('name')->pluck('name', 'id')->all();

    	return view('public.index', compact('events', 'cities'));
    }

    public function by_city($city){

    		if(!$city = City::where('name', $city)->first()){
    		    abort(400);
            }

    		$events = $city->events()->upcoming()->orderBy('date')->offset(0)->limit(8)->get();
    		$cities = City::orderBy('name')->pluck('name', 'id')->all();

		    return view('public.index', compact('events', 'cities', 'city'));
    }

    public function lazy_load($offset, $city = false){

	    if ($city === false){
		    $events = Event::upcoming()->orderBy('date')->offset($offset)->limit(8)->get();
	    } else{
		    $events = Event::where('city_id', $city)->upcoming()->orderBy('date')->offset($offset)->limit(8)->get();
	    }

		    if($events->count() > 0){
			    $html = view('public.load', compact('events', 'offset'))->render();
			    return response()->json(['status' => "success", 'html' => $html]);
		    }else{
			    return response()->json(['status' => "failure"]);
		    }
    }

    public function past($city = false){
    	if($city === false){
    		$events = Event::past()->orderBy('date', 'desc')->get();
    		$events = $events->groupBy('name');
    		$count = $events->count();
    		$keys = $events->keys();
		    return view('public.past.all', compact('events', 'count', 'keys'));
	    }else{
		    if(!$city = City::where('name', $city)->first()){
			    abort(400);
		    }

		    $events = $city->events()->past()->orderBy('date', 'desc')->get();
		    $events = $events->groupBy('name');
		    $count = $events->count();
		    $keys = $events->keys();
		    return view('public.past.city', compact('city', 'events', 'count', 'keys'));
	    }

    }
}
