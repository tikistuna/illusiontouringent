<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriberStatistic extends Model
{
    protected $dates = ['date'];
    protected $fillable = ['date', 'phones', 'phones_verified', 'emails', 'emails_verified'];
}
