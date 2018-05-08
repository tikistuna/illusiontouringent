<?php

namespace App\Listeners;

use App\Events\TextMessageSent;
use App\Models\Phone;
use App\Models\TextRecord;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class LogTextMessage
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  TextMessageSent  $event
     * @return void
     */
    public function handle(TextMessageSent $event)
    {
    	if($phone = Phone::where('phone', $event->response->msisdn)->first()){
		    $input['phone_id'] = $phone->id;
	    }
	    $input['code'] = $event->response->{'err-code'};
	    $input['status'] = $event->response->status;
	    $input['phone_number'] = $event->response->msisdn;
	    $input['text_record_id'] = $event->response->messageId;
	    $input['cost'] = $event->response->price;
	    $input['action'] = 'Text Message';

	    TextRecord::create($input);
    }
}
