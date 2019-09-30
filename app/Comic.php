<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Comic extends Model
{
    use SoftDeletes;
    protected $fillable = ['file_path', 'title', 'cover_id'];
    protected $hidden = ['pivot'];
    public function isActive()
    {
        $this->hasOne('App\ActiveComic')->exists();
    }

    public function activeComic(){
        return $this->hasOne('App\ActiveComic','comic_id');
    }

    public function images()
    {
        return $this->hasManyThrough('App\Image', 'App\ActiveComic');
    }

    public function parentDir(){
        return $this->belongsTo('App\Directory');
    }

    public function cover(){
        return $this->belongsTo('App\Cover');
    }

    public function authors(){
        return $this->belongsToMany('App\Author')->withPivot('role');
    }

    public function series(){
        return $this->belongsToMany('App\Series');
    }

    public function publishers(){
        return $this->belongsToMany('App\Publisher');
    }

    public function collections(){
        return $this->morphToMany('App\Collection', 'collectionable');
    }

    public function tags() {
        return $this->morphToMany('App\Tag', 'taggable');
    }

    public function readableBy($user){
        if($user) {
            if($rule = $this->users()->withPivot('allow')->first())
                return $rule->allow;
            if(security_settings['recursive_rule']['value']){
                if($rule = $this->tags()->join('readables','readable_id','=','tags.id')->where([['readable_type','App\Tag'],['user_id',$user->id]])->select('readables.allow')->first())
                    return $rule->allow;
                if($rule = $this->series()->join('readables', 'series.id', '=', 'readable_id')->where([['readable_type','series']])->select('allow')->first())
                    return $rule->allow;
            }
        }
        return security_settings['default_rule']['value'];
    }

    public function users(){
        return $this->morphToMany('App\User', 'readable');
    }

    public static function readable() {
        return Auth::user() ? Auth::user()->readableComics() : new self();
    }

    public static function readableIds() {
        return  self::readable()->select('id')->get();
    }

    public static function notReadable() {
        return self::all()->reject(function($comic){
            return $comic->readableBy(Auth::user());
        });
    }

    public static function notReadableIds() {
        return self::notReadable()->map(function ($comic){
            return $comic->id;
        });
    }

}
