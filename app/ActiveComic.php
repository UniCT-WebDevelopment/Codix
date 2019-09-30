<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ActiveComic extends Model
{

    protected $fillable = ['id'];

    public function comic()
    {
        return $this->hasOne('App\Comic','id');
    }

    public function images()
    {
        return $this->hasMany('App\Image','comic_id');
    }


}
