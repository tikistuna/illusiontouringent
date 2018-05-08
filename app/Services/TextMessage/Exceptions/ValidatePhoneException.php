<?php


	namespace App\Services\TextMessage\Exceptions;


	use App\Models\User;
	use App\Notifications\PhoneValidationError;
	use Exception;

	class ValidatePhoneException extends Exception
	{
		public function report(){
			/**
			 * Send to Slack!
			 */
			User::find(1)->notify(new PhoneValidationError($this->getMessage()));
		}
	}