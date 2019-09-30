<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cover extends Model
{
    protected $fillable = ['cover_path'];

    public function comics(){
        return $this->hasMany('App\Comic');
    }

    public function directories()
    {
        return $this->hasMany('App\Directory');
    }

    public function series()
    {
        return $this->hasMany('App\Series');
    }

    public function collections()
    {
        return $this->hasMany('App\Collection');
    }
}
