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
use App\Services\Url\Exceptions\UrlGetClicksException;
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
    	$cities = City::orderBy('name')
	                    ->pluck('name', 'id')
		                ->all();
	    $venues_raw = Venue::orderBy('name')
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
        	'venue_id' => 'bail|required|numeric|exists:venues,id',
	        'name' => 'required|string',
	        'date' => 'required|date',
	        'description' => 'required|string',
	        'reminder_description' => 'required|string',
	        'prices' => 'required|string',
	        'illusion' => 'nullable|boolean',
        ]);

        $illusion = $request->illusion ?? 0;
        $event = Event::create([
        	'name' => $request->name,
	        'date' => $request->date,
	        'description' => $request->description,
	        'reminder_description' => $request->reminder_description,
	        'venue_id' => $request->venue_id,
	        'illusion' => $illusion
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
	    $cities = City::orderBy('name')
		    ->pluck('name', 'id')
		    ->all();
	    $venues = Venue::orderBy('name')
		    ->pluck('name', 'id')
		    ->all();
    	$event = Event::findOrFail($id);
    	$date = $event->date->toDateTimeString();

        return view('admin.events.edit', compact('event', 'cities', 'venues', 'date'));
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
		    'venue_id' => 'bail|required|numeric|exists:venues,id',
		    'name' => 'required|string',
		    'date' => 'required|date',
		    'description' => 'required|string',
		    'reminder_description' => 'required|string',
		    'illusion' => 'nullable|boolean',
	    ]);

        $event = Event::findOrFail($id);

        $illusion = $request->illusion ?? 0;

	    $event->update([
		    'name' => $request->name,
		    'date' => $request->date,
		    'description' => $request->description,
		    'venue_id' => $request->venue_id,
		    'reminder_description' => $request->reminder_description,
		    'illusion' => $illusion,
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
	 * @param UrlShortener $urlShortener
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function eventTicketSeller(Request $request, $id, UrlShortener $urlShortener){
	    if($request->isMethod('get')){
	    	if($urlShortener->isAccessTokenExpired()){
	    		session(['url' => 'admin/eventTicketSeller/'. $id]);
	    		return redirect('admin/oauth');
		    }
			$event = Event::findOrFail($id);
	    	$ticket_sellers = TicketSeller::orderBy('name')->pluck('name', 'id')->all();
	    	return view('admin.events.ticket_seller_create', compact('ticket_sellers', 'event'));

	    }elseif($request->isMethod('post')){
		    $request->validate([
			    'ticket_seller_id' => 'required|numeric|exists:ticket_sellers,id',
			    'website' => 'nullable|url',
		    ]);
			$event = Event::findOrFail($id);

			$website = !is_null($request->website) ? $urlShortener->shortenUrlWithOauth($request->website) : null;

			$event->ticket_sellers()->attach($request->ticket_seller_id, ['website' => $website]);


			return redirect('/admin/events');

	    }
    }

	/**
	 * @return string
	 */
	public function api_index(){

		return Event::orderBy('date', 'desc')->get()->toJson();


	}

	public function api_last_created(){
	    $date = Event::orderBy('created_at', 'desc')->pluck('created_at')->first();
	    return json_encode(['lastCreated' => $date->diffForHumans()]);
    }

    public function toggleEventStatus($id){
		$event = Event::findOrFail($id);
		$event->active = !$event->active;
		$event->save();
		return response('Success', 200);
    }
	/**
	 * @param UrlShortener $urlShortener
	 */
	public function RefreshUrlClicks(UrlShortener $urlShortener){
		$events = Event::upcoming()->get();
		foreach($events as $event){
			if($ticketSeller = $event->ticketSellersWithShortUrl->first()){
				try{
					$event->urlClicks = (int)$urlShortener->getClicks($ticketSeller->pivot->website);
				}catch(UrlGetClicksException $e){
				/**
				 * Getting url stats has been unreliable due to Google's servers, no logging will be done for now
				 */
				}
				$event->save();
			}
		}
    }

    public function sendTextReminders(TextMessager $textMessager){
		$six_weeks = Carbon::now()->addWeeks(6)->toDateString();
	    $four_weeks = Carbon::now()->addWeeks(4)->toDateString();
		$events = Event::whereDate('date', '<', $six_weeks)->whereDate('date', '>', $four_weeks)->where('six_week_reminder_sent', 0)->orderBy('date')->get();
		if($events->count() > 0){
			$event = $events[0];
			$city_id = $event->city->id;
			$subscribers = SuscriberController::phoneSubscribersInCity($city_id);

			if(empty($event->text_message)){
				$event->six_week_reminder_sent = 2;
				$event->save();
				return;
			}

            $event->six_week_reminder_sent = 1;
            $event->save();

            foreach($subscribers as $subscriber){
                try{
                    $textMessager->text($subscriber, $event->text_message);
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
	    $events = Event::whereDate('date', '<', $two_weeks)->whereDate('date', '>', $now)->where('two_week_reminder_sent', 0)->orderBy('date')->get();
	    if($events->count() > 0){
		    $event = $events[0];
		    $city_id = $event->city->id;
		    $subscribers = SuscriberController::phoneSubscribersInCity($city_id);

		    if(empty($event->text_message)){
			    $event->two_week_reminder_sent = 2;
			    $event->save();
			    return;
		    }

            $event->two_week_reminder_sent = 1;
            $event->save();
            foreach($subscribers as $subscriber){
                try{
                    $textMessager->text($subscriber, $event->text_message);
                }catch(TextMessageException $e){
                    $event->six_week_reminder_sent = 2;
                    $event->save();
                    report(new TextMessageException('Automated messaging failed for event: ' . $event->id . ' At subscriber: ' . $subscriber->id));
                    return;
                }

            }

	    }

    }
}
