<?php

namespace App\Http\Controllers;

use App\Contracts\TextMessage\TextMessager;
use App\Events\TextMessageFailed;
use App\Events\TextMessageSent;
use App\Models\City;
use App\Models\Email;
use App\Models\Phone;
use App\Models\SubscriberStatistic;
use App\Models\User;
use App\Notifications\VerificationEmailSendingError;
use App\Services\TextMessage\Exceptions\ValidatePhoneException;
use Carbon\Carbon;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SuscriberController extends Controller
{


	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @param TextMessager $textMessager
	 * @return \Illuminate\Http\Response
	 */
    public function store(Request $request, TextMessager $textMessager)
    {

    	$request->validate([
    		'name' => 'required|nullable|string',
		    'phone' => 'required|nullable|string',
		    'city' => 'required|exists:cities,id',
	    ]);

	    $city = City::findOrFail($request->city);

		/*
		|------------------------------------------------------
		| Phone handler
		|------------------------------------------------------
		|
		*/
		if($request->phone){
			$digits = Phone::getDigits($request->phone);

			if(!$phone = Phone::where('phone', '=', $digits)->first()){
				$input = $request->all();
				$input['validation_code'] = SuscriberController::token_generator(32);
				$input['phone'] = $digits;
				$phone = Phone::create($input);
			}

			if($phone->blacklisted){
				$request->session()->flash('message_phone', "Lo sentimos, su tel&eacute;fono no puede ser verificado.");
				return redirect('/');
			}

			if($phone->status){
				$phone->suscriptions()->syncWithoutDetaching([$city->id => ['citiable_type' => 'App\Models\Phone']]);
				$request->session()->flash('message_phone', "Hemos procesado su suscripci&oacute;n exit&oacute;samente (Tel&eacute;fono).");
			}else{
				try{
					$valid = $textMessager->validatePhone($phone);
					if($valid){
						$phone->suscriptions()->syncWithoutDetaching([$city->id => ['citiable_type' => 'App\Models\Phone']]);
						$request->session()->flash('message_phone', "Hemos procesado su suscripci&oacute;n exit&oacute;samente (Tel&eacute;fono).");
					}else{
						$phone->blacklisted = true;
						$phone->save();
						$request->session()->flash('message_phone', "Lo sentimos, su celular no es v&aacute;lido. Verifique que haya ingresado su celular correctamente.");
					}
				}catch(ValidatePhoneException $e){
					report($e);
					$request->session()->flash('message_phone', "Lo sentimos, hubo un error al intentar verificar su tel&eacute;fono. Intente m&aacute;s tarde.");
				}
			}
		}


		return redirect('/');
    }


    public function destroy(Request $request)
    {
	    $request->validate([
		    'phone' => 'required|nullable|string',
		    'city' => 'required|exists:cities,id|numeric',
	    ]);

	    $city = $request->city;
	    if(!settype($city, "integer")){
	    	abort(500);
	    }

	    $digits = Phone::getDigits($request->phone);
	    if($phone = Phone::where('phone', '=', $digits)->first()){

	    	$phone->suscriptions()->detach($city);
	    	$request->session()->flash('message_phone', "Hemos cancelado su suscripci&oacute;n (mensajes de texto)");
	    }else{
	    	$request->session()->flash('message_phone', "Error: Este tel&eacute;fono no est&aacute; suscrito a esta ciudad");
	    }


	    return redirect('/');
    }


    public function destroy_show()
    {
	    $cities = City::orderBy('name')
		    ->pluck('name', 'id')
		    ->all();
	    return view('suscribers.destroy', compact('cities'));
    }




	/**
	 * Modify the following code to make one universal blade and yield the wanted message
	 */


	/**
	 * Validate the specified phone number
	 *
	 * @param Request $request
	 * @param $phone
	 * @param $validation_code
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
    public function validate_phone(Request $request, $phone, $validation_code){
		if($phone = Phone::where('phone', '=', $phone)->first()){
			if($phone->validation_code === $validation_code){
				$phone->validation_code = 0;
				$phone->status = 1;
				$phone->save();
			}else{
				if(!$phone->status){
					abort(400);
					die();
				}
			}
		}else{
			abort(400);
			die();
		}
	    $request->session()->flash('message_phone', "Su tel&eacute;fono ha sido verificado.");
		return redirect('/');
    }

	public static function token_generator($number){
		$token = openssl_random_pseudo_bytes($number);
		$token = bin2hex($token);
		return $token;
	}

	public function deliveryReceipt(Request $request){
    	if($request->has(['messageId', 'status'])){
			if((int)$request->input('err-code') === 0){
				event(new TextMessageSent($request));
				return response('OK', 200);
			}else{
				event(new TextMessageFailed($request));
				return response('OK', 200);
			}

		}else{
    		abort(400);
	    }
	}

	public static function phoneSubscribersInCity($city_id){
    	return Phone::whereHas('suscriptions', function($query) use ($city_id){
    		    $query->where('cities.id', $city_id);
    	})->where('status', 1)->where('blacklisted', 0)->orderBy('id')->get();
	}

	public function getStats(){
    	$phones = Phone::all()->count();
    	$phones_verified = Phone::where('status', 1)->get()->count();
    	$date = Carbon::now()->toDateTimeString();
    	SubscriberStatistic::create(compact('date', 'phones', 'phones_verified'));
	}
}

