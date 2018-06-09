<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    protected $fillable = [
        'event_id',
	    'folder_id',
	    'path'
    ];

    public function event(){
    	return $this->belongsTo('App\Models\Event');
    }

	public function folder(){
		return $this->belongsTo('App\Models\Folder');
	}

	public function getNameAttribute(){
        $pos = strrpos($this->path, '/');
        return substr($this->path, $pos+1);
    }

    public function getPathAttribute($path){
    	return 'https://ljconciertos.com' . $path;
    }
}
