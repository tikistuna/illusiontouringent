<?php

namespace App\Listeners;

use App\Events\PhoneValidationPerformed;
use App\Models\TextRecord;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class LogPhoneValidationRecord
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
     * @param  PhoneValidationPerformed  $event
     * @return void
     */
    public function handle(PhoneValidationPerformed $event)
    {
		$input['text_record_id'] = $event->body->request_id;
		$input['code'] = $event->body->status;
		$input['status'] = $event->body->status_message;
		$input['cost'] = $event->body->request_price;
		$input['phone_id'] = $event->phone->id;
		$input['action'] = 'Validation';
		TextRecord::create($input);
    }
}
