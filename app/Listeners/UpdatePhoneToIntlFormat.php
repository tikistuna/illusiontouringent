<?php

namespace App\Listeners;

use App\Events\PhoneValidationPerformed;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class UpdatePhoneToIntlFormat
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
        if($event->body->international_format_number != null){
        	$event->phone->phone = $event->body->international_format_number;
        	$event->phone->save();
        }
    }
}
