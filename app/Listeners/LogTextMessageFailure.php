<?php

namespace App\Listeners;

use App\Events\TextMessageFailed;
use App\Models\Phone;
use App\Models\TextError;
use App\Models\TextRecord;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class LogTextMessageFailure
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
     * @param  TextMessageFailed  $event
     * @return void
     */
    public function handle(TextMessageFailed $event)
    {
	    if($phone = Phone::where('phone', $event->response->msisdn)->first()){
		    $input['phone_id'] = $phone->id;
	    }
	    $input['code'] = $event->response->{'err-code'};
	    $input['status'] = $event->response->status;
	    $input['phone_number'] = $event->response->msisdn;
	    $input['text_record_id'] = $event->response->messageId;
	    $input['cost'] = $event->response->price ?? 0.00;
	    $input['action'] = 'Text Message (Failure)';

	    $textRecord = TextRecord::create($input);
	    $textRecord->text_errors()->create([
	    	'error' => $event->response->{'err-code'}
	    ]);
    }
}
