<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Series extends Model
{
    protected $fillable = ['name'];
    protected $hidden = ['pivot'];
    public function comics(){
        return $this->belongsToMany('App\Comic');
    }
    public function publisher() {
        return $this->belongsTo('App\Publisher');
    }

    public function collections() {
        return $this->morphToMany('App\Collection', 'collectionable');
    }

    public function cover() {
        return $this->belongsTo('App\Cover');
    }

    public function coverUrl() {
        if($cover = $this->cover()->first())
            return $cover->id;
        if($comic = $this->comics()->first())
            return $comic->cover_id;
    }

    public function users() {
        return $this->morphToMany('App\User', 'readable');
    }

    public function readableBy($user) {
        if($user){
            if($rule = $this->users()->withPivot('allow')->select('allow')->find($user->id))
                return $rule->allow == 1;
        }
        return security_settings['default_rule']['value'];
    }

    public static function readable() {
        return Auth::user() ? Auth::user()->readableSeries() : new self;
    }

    public static function readableIds() {
        return self::readable()->select('id')->get();
    }

    public static function notReadable() {
        return self::all()->reject(function ($series) {
            return $series->readableBy(Auth::user());
        });
    }

    public static function notReadableIds() {
        return is_null(Auth::user()) ? [] : self::notReadable()->map(function ($series){
            return $series->id;
        });
    }

    public function readableComics() {

        if(is_null(Auth::user())) return $this->comics();

        // Fa 2 query
        if (security_settings['recursive_rule']['value']) 
            return $this->comics()->whereNotIn('id', Comic::notReadableIds());
        
        // Fa sempre una sola query
        return
            security_settings['default_rule']['value']
            ? $this->comics()->whereNotIn('id', Auth::user()->comics()->wherePivot('allow', false)->select('comics.id')->get())
            : $this->comics()->whereIn('id', Auth::user()->comics()->wherePivot('allow', true)->select('comics.id')->get());
    }

}
