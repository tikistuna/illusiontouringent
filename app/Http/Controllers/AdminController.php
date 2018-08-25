<?php

namespace App\Http\Controllers;

use App\Contracts\UrlShortener\UrlShortener;
use App\Models\City;
use App\Models\Email;
use App\Models\Event;
use App\Models\InboundMail;
use App\Models\Phone;
use App\Models\SubscriberStatistic;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;


class AdminController extends Controller
{

	public function index(){

		$today = Carbon::now();
		$thirty_days_ago = $today->subDays(30);
		$stats = SubscriberStatistic::whereDate('date', '>', $thirty_days_ago)->get();
		$data = implode(', ', $stats->pluck('phones_verified')->all());
		$dates = $stats->pluck('date');
		$dates_str = [];
		foreach($dates as $date){
			$dates_str[] = '"' . $date->format("M j") . '"';
		}
		$dates = implode(',', $dates_str);
		$min = $stats->min('phones_verified');
		$max = $stats->max('phones_verified');
		$diff = $max - $min;
		$max += intdiv($diff, 8);
		$min -= intdiv($diff, 8);
		return view('admin.index', compact( 'data', 'min', 'max', 'dates'));
	}

	public function excel(Request $request){
		if($request->isMethod('get')){
			$events = Event::upcoming()->orderBy('date')->get();
			return view('admin.excel', compact('events'));
		}
	}

	public function oauth(Request $request, UrlShortener $urlShortener){
		return $urlShortener->oauth($request);
	}

	public function mailRecipient(Request $request){
		if(!($request->has(['token', 'timestamp', 'signature']))){
			abort(400);
		}

		if($this->validMailgunWebhook($request->token, $request->timestamp, $request->signature)){
			InboundMail::create($request->all());
		}
		abort(400);
	}

	private function validMailgunWebhook($token, $timestamp, $signature){
		return hash_hmac('sha256', $timestamp.$token, env('MAILGUN_SECRET')) === $signature;
	}

	/**
	 * Need to update both functions below to use new TextMessager interface, also need to update and better the system for uploading files
	 */

	public function parse_file(Request $request){
    	if($request->isMethod('get')){
    		return view('dev/parse_file');
	    }elseif($request->isMethod('post')){
		    $request->validate([
			    'name' => 'required|string',
		    ]);
		    $path = env('PATH_TO_MAILING_LIST_FILES') . $request->name;
		    $mailing_list = fopen($path, 'r');
		    if($mailing_list){
			    $lines = [];
			    while(($line = fgets($mailing_list)) !== false){
			    	$line_as_list = explode(' ', trim($line));
					$lines[] = $line_as_list;
			    }
			    fclose($mailing_list);

			    $city = City::where('name', 'San Jose')->first();

			    foreach($lines as $record){
			    	self::parse_record($record, $city);
			    }

		    }else{
			    echo "Error opening file";
		    }
	    }
	}


	/**
	 * @param $record, an array of 4 elements: first name, last name, phone, email
	 *
	 */
	public static function parse_record($record, $city){
		if($record[3] !== 'empty' and $record[3] !== ''){

			if(!$email = Email::where('email', '=', $record[3])->first()){
				$input = [];
				$input['name'] = $record[0] . ' ' . $record[1];
				$input['email'] = $record[3];
				$input['city_id'] = $city->id;
				$email = Email::create($input);
				$email->suscriptions()->attach($city->id, ['citiable_type' => 'App\Models\Email']);

			}else{
				$suscribed = false;
				foreach($email->suscriptions as $suscription){
					if($suscription->id === $city->id){
						$suscribed = true;
						break;
					}
				}
				if(!$suscribed){
					$email->suscriptions()->attach($city->id, ['citiable_type' => 'App\Models\Email']);
				}
			}
		}

		if($record[2] !== 'empty' and $record[2] !== ''){

			$digits = Phone::getDigits($record[2]);

			if(!$phone = Phone::where('phone', '=', $digits)->first()){

				$client = new Client([
					'base_uri' => 'https://app.eztexting.com/sending/phone-numbers/',
					'allow_redirects' => false,
					'http_errors' => false,
				]);

				$url = 'https://app.eztexting.com/sending/phone-numbers/' . $digits . '?User=' . env('EZTEXTING_USER') . '&Password=' . env('EZTEXTING_PASSWORD') . '&format=json';

				$response = $client->request('GET', $url);
				$body = $response->getBody();
				$body = json_decode($body);
				$body = $body->Response;
				$code = $body->Code;

				if($code === 200){
					$input = [];
					$input['name'] = $record[0] . ' ' . $record[1];
					$input['phone'] = $digits;
					$input['city_id'] = $city->id;
					$input['status'] = 1;
					$phone = Phone::create($input);
					$phone->suscriptions()->attach($city->id, ['citiable_type' => 'App\Models\Phone']);
				}

			}else{
				$suscribed = false;
				foreach($phone->suscriptions as $suscription){
					if($suscription->id === $city->id){
						$suscribed = true;
						break;
					}
				}
				if(!$suscribed){
					$phone->suscriptions()->attach($city->id, ['citiable_type' => 'App\Models\Phone']);
				}

				if($phone->status === 0){

					$client = new Client([
						'base_uri' => 'https://app.eztexting.com/sending/phone-numbers/',
						'allow_redirects' => false,
						'http_errors' => false,
					]);

					$url = 'https://app.eztexting.com/sending/phone-numbers/' . $digits . '?User=' . env('EZTEXTING_USER') . '&Password=' . env('EZTEXTING_PASSWORD') . '&format=json';

					$response = $client->request('GET', $url);

					$body = $response->getBody();
					$body = json_decode($body);
					$body = $body->Response;
					$code = $body->Code;

					if($code === 200){
						$phone->status = 1;
						$phone->save();
					}
				}
			}
		}
	}
}
