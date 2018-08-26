<?php


	namespace App\Services\TextMessage;


	use App\Contracts\TextMessage\TextMessager;
	use App\Events\PhoneValidationFailed;
	use App\Events\PhoneValidationPerformed;
	use App\Models\Phone;
    use App\Models\User;
    use App\Notifications\PhoneValidationError;
    use App\Services\TextMessage\Exceptions\TextMessageException;
	use App\Services\TextMessage\Exceptions\ValidatePhoneException;
	use GuzzleHttp\Client;
    use GuzzleHttp\Exception\GuzzleException;
    use Illuminate\Support\Facades\Log;

	class NexmoTextingService implements TextMessager
	{

		private $client;
		private $key;
		private $secret;
		private $from;
		private $textUrl;
		private $validateUrl;

		public function __construct(Client $client, $key, $secret, $from){
			$this->client = $client;
			$this->key = $key;
			$this->secret = $secret;
			$this->from = $from;
			$this->textUrl = 'https://rest.nexmo.com/sc/us/alert/json?api_key=' . $this->key . '&api_secret=' . $this->secret;
			$this->validateUrl = 'https://api.nexmo.com/ni/advanced/json' . '?api_key=' . $this->key . '&api_secret=' . $this->secret . '&number=';
		}

        /**
         * @param mixed $number
         * @param mixed $message
         * @return bool|mixed|\Psr\Http\Message\ResponseInterface
         * @throws TextMessageException
         */
        public function text($number, $message){

			if($number instanceof Phone){
				if(env('APP_ENV') === 'local'){
					return $this->mock_text($number, $message);
				}
				return $this->text_phone($number, $message);
			}
		}

		/**
		 * @param Phone $phone
		 * @param $message
		 * @return mixed|\Psr\Http\Message\ResponseInterface
		 * @throws TextMessageException
		 */
		private function text_phone(Phone $phone, $message){
            $url = $this->getTextUrl($phone->phone, $message);

		    try{
                $response = $this->client->request('GET', $url);
                return $response;
            }catch(GuzzleException $e){
                Log::error('Guzzle Exception Text Message Error');
                throw new TextMessageException('Guzzle Error while texting: ' . $phone->phone);
            }


		}

        /**
         * @param Phone $phone
         * @return bool
         * @throws ValidatePhoneException
         */
        public function validatePhone(Phone $phone){
		    try{
                $response = $this->client->request('GET', $this->validateUrl . $phone->phone . '&country=US');
                $body = json_decode($response->getBody());
                if(((int)$body->status) === 0 and $body->valid_number !== 'unknown'){
                    $validity = ($body->valid_number === 'valid');
                    event(new PhoneValidationPerformed($body, $phone));
                    return $validity;
                }else{
                	if(isset($body->request_id)){
		                Log::error('Nexmo Validation Error', ['Request Id' => $body->request_id]);
	                }else{
                		Log::error('Nexmo Validation Error', ['Response Body' => json_encode($body)]);
	                }

                    event(new PhoneValidationFailed($phone, $body));
                    throw new ValidatePhoneException('Nexmo Validation Error. Phone: ' . $phone->phone);
                }
            }catch(GuzzleException $exception){
                Log::error('Guzzle Exception Validation Error', ['Phone: ' => $phone->phone]);
                throw new ValidatePhoneException('Guzzle Error During Validation. Phone: ' . $phone->phone);
            }

		}

        /**
         * @param $to
         * @param $message
         * @return string
         * @throws TextMessageException
         */
        private function getTextUrl($to, $message){
			if(!is_array($message)){
				throw new TextMessageException('Message not an array. Phone: ' . $to);
			}
			return $this->textUrl . '&to=' . $to . '&' . http_build_query($message);
		}

		private function mock_text(Phone $phone, $message){
			Log::info('Sent message: ', ['Phone: '=>$phone->phone, 'message' => http_build_query($message)]);
		}

	}