<?php

namespace App\Listeners;

use App\Contracts\TextMessage\TextMessager;
use App\Contracts\UrlShortener\UrlShortener;
use App\Events\PhoneValidationPerformed;
use App\Services\Url\Exceptions\UrlShorteningException;


class SendVerificationTextMessage
{

	private $urlShortener;
	private $textMessager;
	/**
	 * Create the event listener.
	 *
	 * @param UrlShortener $urlShortener
	 * @param TextMessager $textMessager
	 */
    public function __construct(UrlShortener $urlShortener, TextMessager $textMessager)
    {
    	$this->urlShortener = $urlShortener;
    	$this->textMessager = $textMessager;
    }

    /**
     * Handle the event.
     *
     * @param  PhoneValidationPerformed  $event
     * @return void
     */
    public function handle(PhoneValidationPerformed $event)
    {
	    $url =  env('APP_URL') . '/suscribers/verification/phone/' . $event->phone->phone . '/' . $event->phone->validation_code;
	    try{
            $shortenedurl =	$this->urlShortener->shortenUrl($url);
            $message = ['event' => 'L.J. Productions: Verifique su telefono haciendo click', 'venue' =>'siguiente enlace', 'date' => $shortenedurl, 'description' => ' .'];
            $this->textMessager->text($event->phone, $message);
        }catch(UrlShorteningException $e){
	    	report($e);
            $message = ['event' => 'L.J. Productions:', 'venue' =>'. ', 'date' => $url, 'description' => ' .'];
            $this->textMessager->text($event->phone, $message);
        }

    }
}
