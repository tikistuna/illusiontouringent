<?php

namespace App\Services\Url\Exceptions;


use App\Models\User;
use App\Notifications\GoogleShortenerFailure;
use Exception;

class UrlGetClicksException extends Exception
{
    public function report(){
        /**
         * Send to Slack!
         */
        User::find(1)->notify(new GoogleShortenerFailure($this->getMessage()));
    }
}