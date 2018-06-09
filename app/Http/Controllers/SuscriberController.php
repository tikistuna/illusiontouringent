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
    		'name' => 'nullable|string',
		    'email' => 'required_without:phone|nullable|email',
		    'phone' => 'required_without:email|nullable|string',
		    'city' => 'required|exists:cities,id',
	    ]);

	    $city = City::findOrFail($request->city);

		/*
		|------------------------------------------------------
		| Email handler
		|------------------------------------------------------
		|
		*/
		if($request->email){
			/**
			 * If email exists, pull the record. Else create a new entry
			 */

			if(!$email = Email::where('email', '=', $request->email)->first()){
				$input = $request->all();
				$input['validation_code'] = SuscriberController::token_generator(32);
				$email = Email::create($input);
				$created = true;
			}

			$email->suscriptions()->syncWithoutDetaching([$city->id => ['citiable_type' => 'App\Models\Email']]);


			if(!$email->status){

				$email_data = [
					'email' => $email->email,
					'name' => $email->name,
					'validation_code' => $email->validation_code,
					'city' => $city->name,
				];

				/**
				 * Use Exception Handler for Mailgun API
				 */
				try{
					Mail::send('emails.verification', $email_data, function($m) use ($email){
						$m->from('verification@illusiontouringentertainment.com', 'Illusion Touring Entertainment');
						$name = $email->name ? $email->name : 'Estimado Suscriptor';
						$m->to($email->email, $name)->subject('Verificación de e-mail');
					});
					$request->session()->flash('message_email', "Le hemos enviado un correo con m&aacute;s informaci&oacute;n");
				} catch(GuzzleException $e){

					/**
					 * Trigger an event to report through slack
					 */
						Log::error('Mailgun API Exception', ['Exception Message' => $e->getMessage()]);
						User::find(1)->notify(new VerificationEmailSendingError($e->getMessage()));
						if(isset($created) and $created){
							$email->suscriptions()->detach();
							$email->delete();
							$request->session()->flash('message_email', "Ha ocurrido un error al registrar su email, por favor intente de nuevo.");
						}else{
							$request->session()->flash('message_email', "Por favor verifique su email haciendo click en el enlace que le hemos enviado previamente a su email");
						}
				}
			}else{
				$email_data = [
					'name' => $email->name,
					'city' => $city->name,
				];

				try{
					Mail::send('emails.verified', $email_data, function($m) use ($email){
						$m->from('verification@illusiontouringentertainment.com', 'Illusion Touring Entertainment');
						$name = $email->name ? $email->name : 'Estimado Suscriptor';
						$m->to($email->email, $name)->subject('Verificación de e-mail');
					});

					$request->session()->flash('message_email', "Le hemos enviado un correo con m&aacute;s informaci&oacute;n");
				} catch(GuzzleException $e){
					/**
					 * Trigger an event to report through slack
					 */
					Log::error('Mailgun API Exception', ['ExceptionMessage' => $e->getMessage()]);
					User::find(1)->notify(new VerificationEmailSendingError($e->getMessage()));
					$request->session()->flash('message_email', "Hemos procesado su suscripci&oacute;n exit&oacute;samente (email).");
				}

			}
		}


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
		    'email' => 'required_without:phone|nullable|email',
		    'phone' => 'required_without:email|nullable|string',
		    'city' => 'required|exists:cities,id|numeric',
	    ]);

	    $city = $request->city;
	    if(!settype($city, "integer")){
	    	abort(500);
	    }

	    if($request->phone){
			$digits = Phone::getDigits($request->phone);
			if($phone = Phone::where('phone', '=', $digits)->first()){

				$phone->suscriptions()->detach($city);
				$request->session()->flash('message_phone', "Hemos cancelado su suscripci&oacute;n (mensajes de texto)");

			}else{
				$request->session()->flash('message_phone', "Error: Este tel&eacute;fono no est&aacute; suscrito a esta ciudad");
			}
	    }

	    if($request->email){
		    if($email = Email::where('email', '=', $request->email)->first()){

			    $email->suscriptions()->detach($city);
			    $request->session()->flash('message_email', "Hemos cancelado su suscripci&oacute;n (email)");

		    }else{
			    $request->session()->flash('message_email', "Error: Este tel&eacute;fono no est&aacute; suscrito a esta ciudad");
		    }
	    }

	    return redirect('/');
    }


    public function destroy_show()
    {
	    $cities = City::orderBy('name', 'asc')
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

	/**
	 * Validate the specified email
	 *
	 * @param $email
	 * @param $validation_code
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function validate_email(Request $request, $email, $validation_code){
		if($email = Email::where('email', '=', $email)->first()){
			if($email->validation_code === $validation_code){
				$email->validation_code = 0;
				$email->status = 1;
				$email->save();
			}else{
				if(!$email->status){
					abort(400);
					die();
				}
			}
		}else{
			abort(400);
			die();
		}
		$request->session()->flash('message_email', "Su email ha sido verificado");
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
    	})->where('status', 1)->where('blacklisted', 0)->orderBy('id', 'asc')->get();
	}

	public function getStats(){
    	$phones = Phone::all()->count();
    	$phones_verified = Phone::where('status', 1)->get()->count();
    	$emails = Email::all()->count();
    	$emails_verified = Email::where('status', 1)->get()->count();
    	$date = Carbon::now()->toDateTimeString();
    	SubscriberStatistic::create(compact('date', 'phones', 'phones_verified', 'emails', 'emails_verified'));
	}
}

