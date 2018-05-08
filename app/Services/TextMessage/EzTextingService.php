<?php

namespace App\Services\TextMessage;

use App\Contracts\TextMessage\TextMessager;
use App\Events\TextMessageFailed;
use App\Events\TextMessageSent;
use App\Models\Phone;
use App\Services\TextMessage\Exceptions\TextMessageFailureException;
use GuzzleHttp\Client;

class EzTextingService implements TextMessager
{
    private $base_url = 'https://app.eztexting.com/';
    private $user;
    private $password;
    private $client;

    /**
     * EzTextingService constructor.
     * @param  Client $client GuzzleHttp Client
     * @param string $user Username for Ez Texting
     * @param string $password Password for Ez Texting
     */
    public function __construct(Client $client, $user, $password){
        $this->user = $user;
        $this->password = $password;
        $this->client = $client;
    }

	/**
	 * @param $send_to mixed Who to send the message to. Can be Phone Model, Collection of Phones, or Ez Texting Group
	 * @param string $message
	 * @return bool
	 */
    public function text($send_to, $message){
    	/*
    	 * Check whether we are sending to an Ez Texting Group, an array of phone numbers or a single phone number
    	 * and handle in two different private methods
    	 */
	    if($send_to instanceof Phone){
	    	return $this->text_phone($send_to, $message);
	    }
    }

    private function text_phone(Phone $phone, $message){
    	/*if(env('APP_ENV') === 'local'){
    		return $this->mock_text_phone($phone, $message);
	    }*/

    	$response = $this->client->request('POST', 'https://app.eztexting.com/sending/messages?format=json',
		    [
			    'form_params' => [
				    'User' => $this->user,
				    'Password' => $this->password,
				    'PhoneNumbers' => [$phone->phone],
				    'Message' => $message,
			    ]
		    ]
	    );

	    $body = json_decode($response->getBody());
	    $body = $body->Response;
	    if($body->Code !== 201){
		    event(new TextMessageFailed($phone, $body));
		    return false;
	    }

	    event(new TextMessageSent($phone, $body));
	    return true;
    }

    private function mock_text_phone(Phone $phone, $message){

    	/**
	     * Mocks an error response from Ez Texting
	     */
    	$body = new \stdClass();
		$body->Code = 403;
		$body->Status = 'Failure';
		$errors = ['Some Error Text Message'];
		$body->Errors = $errors;
		event(new TextMessageFailed($phone, $body));
    	return false;

    	/**
	     * Mocks a successful response from Ez Texting
	     */
	    /*$body = new \stdClass();
	    $body->Code = 201;
	    $body->Status = 'Success';
	    $entry = new \stdClass();
	    $entry->ID = '987653212';
	    $entry->Credits = 1;
	    $body->Entry = $entry;
	    event (new TextMessageSent($phone, $body));
		return true;*/
    }

    private function get_uri($action){
        if($action === 'text'){
            return '/sending/messages?';
        }
    }
}