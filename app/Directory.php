<?php

namespace App;

use App\Http\Resources\ComicCollection;
use App\Http\Resources\DirectoryCollection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Directory extends Model
{

    protected $fillable = ['path','name','directory_id', 'cover_id'];
    //protected $hidden = ['pivot'];
    public function directories(){
        return $this->hasMany('App\Directory');
    }

    public function comics(){
        return $this->hasMany('App\Comic');
    }

    public function parentDir(){
        return $this->belongsTo('App\Directory');
    }

    public function children(){
        return (new DirectoryCollection($this->directories()->get()))->merge(new ComicCollection($this->readableComics(Auth::user())->get()));
    }

    public function cover(){
        return $this->belongsTo('App\Cover');
    }

    public function collections(){
        return $this->morphToMany('App\Collection', 'collectionable');
    }

    public function users(){
        return $this->morphToMany('App\User', 'readable');
    }

    public function readableBy($user){
        if($rule = $this->users()->withPivot('allow')->select('allow')->find($user->id))
            return $rule->allow == 1;
        return security_settings['default_rule']['value'];
    }

    public static function notReadable() {
        return Auth::user()->directories()->wherePivot('allow', false);
    }

    public static function notReadableIds() {
        return is_null(Auth::user()) ? [] : self::notReadable()->pluck('directories.id');
        return self::notReadable()->select('directories.id')->get();
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
            : $this->comics()->wherePivot('allow', true);
    }

    public function readableChildren() {

        $dirs = $this->readableDirectories()->select(['id','name AS name','cover_id','directory_id'])->addSelect(DB::raw("'d' As 'type'"));
        $comics = $this->readableComics()->select(['id','title As name','cover_id', 'directory_id'])->addSelect(DB::raw("'c' As 'type'"));

        return $comics->union($dirs);//->get();
/*
        return $this->readableDirectories()->select(['id','name AS name','cover_id'])->addSelect(DB::raw("'d' As 'type'"))->union($this->readableComics()->select(['id','title As name','cover_id'])->addSelect(DB::raw("'c' As 'type'")));

        return (new DirectoryCollection($this->readableDirectories()->get()))->merge(new ComicCollection($this->readableComics()->get()));
*/
    }

    public function readableDirectories() {
        if(is_null(Auth::user())) return $this->directories();

        return
            security_settings['default_rule']['value']
            ? $this->directories()->whereNotIn('id', self::notReadableIds())
            : $this->directories()->join('readables','directories.id','=','readable_id')->where('readable_type','directory')->wherePivot('allow', true);
    }
}
