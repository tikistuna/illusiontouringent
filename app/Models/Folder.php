<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Folder extends Model
{
    protected $fillable = ['name'];
    protected $appends = ['postersCount'];

    public function path(){
	    return "/assets/" . $this->name;
    }

    public function photos(){
    	return $this->hasMany('App\Models\Photo');
    }

    public function getPostersCountAttribute(){
        return $this->photos->count();
    }
}
