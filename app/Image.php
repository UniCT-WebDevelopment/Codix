<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    public $timestamps = false;

    protected $fillable = ['comic_id', 'image_id', 'image_path'];

    public function comic()
    {
        return $this->belongsTo('App\ActiveComic','comic_id');
    }
}
