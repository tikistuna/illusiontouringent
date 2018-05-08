<?php


	namespace App\Services\TextMessage\Exceptions;

	use App\Models\User;
    use App\Notifications\TextMessageFailure;
    use Exception;

	class TextMessageException extends Exception
	{
		public function report(){
            /**
             * Send to Slack!
             */
            User::find(1)->notify(new TextMessageFailure($this->getMessage()));
		}
	}