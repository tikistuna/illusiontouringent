<?php

namespace App\Http\Controllers;

use App\Contracts\TextMessage\TextMessager;
use App\Contracts\UrlShortener\UrlShortener;
use App\Models\City;
use App\Models\Event;
use App\Models\Folder;
use App\Models\Photo;
use App\Models\Price;
use App\Models\TicketSeller;
use App\Models\User;
use App\Models\Venue;
use App\Notifications\TextMessageFailure;
use App\Services\TextMessage\Exceptions\TextMessageException;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
    	$sort = $request->query('sort') ?? '-date';
		return view('admin.events.index', compact('sort'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    	$cities = City::orderBy('name', 'asc')
	                    ->pluck('name', 'id')
		                ->all();
	    $venues_raw = Venue::orderBy('name', 'asc')
		    ->get();
	    $venues = array();
	    foreach($venues_raw as $venue){
		    $venues[$venue->id] = "{$venue->name} en {$venue->city->name}";
	    }
    	return view('admin.events.create', compact('cities', 'venues'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
        	'city_id' => 'bail|required|numeric|exists:cities,id',
        	'venue_id' => 'bail|required|numeric|exists:venues,id',
	        'name' => 'required|string',
	        'date' => 'required|date',
	        'description' => 'required|string',
	        'reminder_description' => 'required|string',
	        'prices' => 'required|string',
        ]);

        $event = Event::create([
        	'name' => $request->name,
	        'date' => $request->date,
	        'description' => $request->description,
	        'reminder_description' => $request->reminder_description,
	        'city_id' => $request->city_id,
	        'venue_id' => $request->venue_id,
        ]);

        $prices = explode(' ', $request->prices);

        foreach ($prices as $price){
        	Price::create([
        		'event_id' => $event->id,
		        'price' => $price,
	        ]);
        }

	    return redirect('/admin/photos/create');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
	    $cities = City::orderBy('name', 'asc')
		    ->pluck('name', 'id')
		    ->all();
	    $venues = Venue::orderBy('name', 'asc')
		    ->pluck('name', 'id')
		    ->all();
    	$event = Event::findOrFail($id);
    	$date = $event->date->getTimestamp();
    	$hour = date('g', $date);
    	$minute = date('i', $date);
        return view('admin.events.edit', compact('event', 'cities', 'venues', 'date', 'hour', 'minute'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

	    $request->validate([
		    'city_id' => 'bail|required|numeric|exists:cities,id',
		    'venue_id' => 'bail|required|numeric|exists:venues,id',
		    'name' => 'required|string',
		    'date' => 'required|date',
		    'description' => 'required|string',
		    'reminder_description' => 'required|string',
		    'hour' => 'required|numeric',
		    'minute' => 'required|numeric',
	    ]);

        $event = Event::findOrFail($id);

	    $hour = $request->hour + 12;
	    if($request->minute == 0){
		    $date = $request->date . " {$hour}:{$request->minute}0:00";
	    }else{
		    $date = $request->date . " {$hour}:{$request->minute}:00";
	    }


	    $event->update([
		    'name' => $request->name,
		    'date' => $date,
		    'description' => $request->description,
		    'city_id' => $request->city_id,
		    'venue_id' => $request->venue_id,
		    'reminder_description' => $request->reminder_description,
	    ]);

	    return redirect('/admin/events');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Event::findOrFail($id)->delete();
        return redirect('/admin/events');
    }


	/**
	 *  Attach a ticket seller to an event
	 *
	 * @param \Illuminate\Http\Request $request
	 * @param  int $id
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function eventTicketSeller(Request $request, $id){
	    if($request->isMethod('get')){
			$event = Event::findOrFail($id);
	    	$ticket_sellers = TicketSeller::orderBy('name', 'asc')->pluck('name', 'id')->all();
	    	return view('admin.events.ticket_seller_create', compact('ticket_sellers', 'event'));

	    }elseif($request->isMethod('post')){
		    $request->validate([
			    'ticket_seller_id' => 'required|numeric|exists:ticket_sellers,id',
			    'website' => 'nullable|string',
		    ]);
			$event = Event::findOrFail($id);
			if(!is_null($request->website)){
				$event->ticket_sellers()->attach($request->ticket_seller_id, ['website' => $request->website]);
			}else{
				$event->ticket_sellers()->attach($request->ticket_seller_id);
			}

			return redirect('/admin/events');

	    }else{
		    abort(400);
	    }
    }

	/**
	 * @param UrlShortener $urlShortener
	 * @return string
	 * Need to change to a more scalable solution to loading of events with urlClicks
	 */
	public function api_index(){

		return Event::with('city')->orderBy('date', 'desc')->get()->toJson();


	}

	public function api_last_created(){
	    $date = Event::orderBy('created_at', 'desc')->pluck('created_at')->first();
	    return json_encode(['lastCreated' => $date->diffForHumans()]);
    }

	/**
	 * @param UrlShortener $urlShortener
	 */
	public function RefreshUrlClicks(UrlShortener $urlShortener){
		$events = Event::all();
		foreach($events as $event){
			if($ticketSeller = $event->ticketSellersWithShortUrl->first()){
				$event->urlClicks = (int)$urlShortener->getClicks($ticketSeller->pivot->website);
				$event->save();
			}
		}
    }

    public function sendTextReminders(TextMessager $textMessager){
		$six_weeks = Carbon::now()->addWeeks(6)->toDateString();
	    $four_weeks = Carbon::now()->addWeeks(4)->toDateString();
		$events = Event::whereDate('date', '<', $six_weeks)->whereDate('date', '>', $four_weeks)->where('six_week_reminder_sent', 0)->orderBy('date', 'asc')->get();
		if($events->count() > 0){
			$event = $events[0];
			$city_id = $event->city_id;
			$subscribers = SuscriberController::phoneSubscribersInCity($city_id);

			try{
				$message = $this->getReminderDescriptionMessageAsArray($event);
			}catch(TextMessageException $e){
				report($e);
				$event->six_week_reminder_sent = 2;
				$event->save();
				return;
			}

            $event->six_week_reminder_sent = 1;
            $event->save();

            foreach($subscribers as $subscriber){
                try{
                    $textMessager->text($subscriber, $message);
                }catch(TextMessageException $e){
                    $event->six_week_reminder_sent = 2;
                    $event->save();
                    report(new TextMessageException('Automated messaging failed for event: ' . $event->id . ' At subscriber: ' . $subscriber->id));
                    return;
                }

            }

		}

	    $two_weeks = Carbon::now()->addWeeks(2)->toDateString();
		$now = Carbon::now()->toDateString();
	    $events = Event::whereDate('date', '<', $two_weeks)->whereDate('date', '>', $now)->where('two_week_reminder_sent', 0)->orderBy('date', 'asc')->get();
	    if($events->count() > 0){
		    $event = $events[0];
		    $city_id = $event->city_id;
		    $subscribers = SuscriberController::phoneSubscribersInCity($city_id);

		    try{
			    $message = $this->getReminderDescriptionMessageAsArray($event);
		    }catch(TextMessageException $e){
			    report($e);
			    $event->two_week_reminder_sent = 2;
			    $event->save();
			    return;
		    }

            $event->two_week_reminder_sent = 1;
            $event->save();
            foreach($subscribers as $subscriber){
                try{
                    $textMessager->text($subscriber, $message);
                }catch(TextMessageException $e){
                    $event->six_week_reminder_sent = 2;
                    $event->save();
                    report(new TextMessageException('Automated messaging failed for event: ' . $event->id . ' At subscriber: ' . $subscriber->id));
                    return;
                }

            }

	    }

    }

	/**
	 * @param Event $event_model
	 * @return array
	 * @throws TextMessageException
	 */
	protected function getReminderDescriptionMessageAsArray(Event $event_model){
		$matches = [];
		if(preg_match('/<event>(.+)<event>/', $event_model->reminder_description, $matches) !== 1){
			throw new TextMessageException('Error during preg_match -- Automated Message for event: ' . $event_model->id);
		}
		$event = $matches[1];

		if(preg_match('/<venue>(.+)<venue>/', $event_model->reminder_description, $matches) !== 1){
			throw new TextMessageException('Error during preg_match -- Automated Message for event: ' . $event_model->id);
		}
		$venue = $matches[1];

		if(preg_match('/<date>(.+)<date>/', $event_model->reminder_description, $matches) !== 1){
			throw new TextMessageException('Error during preg_match -- Automated Message for event: ' . $event_model->id);
		}
		$date = $matches[1];

		if(preg_match('/<description>(.+)<description>/', $event_model->reminder_description, $matches) !== 1){
			throw new TextMessageException('Error during preg_match -- Automated Message for event: ' . $event_model->id);
		}
		$description = $matches[1];

		return compact('event', 'venue', 'date', 'description');

    }
}
