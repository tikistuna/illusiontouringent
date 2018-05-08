<?php

namespace App\Contracts\TextMessage;

use App\Models\Phone;

Interface TextMessager{

	/**
	 * @param mixed $number Telephone Number to Text
	 * @param $message mixed Message to send
	 * @return boolean sent
	 */
    public function text($number, $message);


    /**
     * @param Phone $phone
     * @return mixed
     *
     */
    public function validatePhone(Phone $phone);
}