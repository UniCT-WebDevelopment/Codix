<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    protected $fillable = ['name'];
    public function comics(){
        return $this->belongsToMany('App\Comic');
    }

    public function readableComics() {
    	return $this->comics();
    }
}
