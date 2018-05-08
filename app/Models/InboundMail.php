<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InboundMail extends Model
{
    protected $casts = ['read' => 'boolean'];
    protected $fillable = ['message', 'from', 'recipient'];
}
