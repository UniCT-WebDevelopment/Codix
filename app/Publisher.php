<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Publisher extends Model
{
    protected $fillable = ['name', 'found_at', 'description'];
    public function comics(){
        return $this->belongsToMany('App\Comic');
    }

    public function readableComics() {
    	return $this->comics();
    }

    public function series(){
        return $this->hasMany('App\Series');
    }
}
