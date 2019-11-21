<?php

namespace App;
use App\Comic;
use App\Series;
use App\Http\Resources\ComicCollection;
use App\Http\Resources\DirectoryCollection;
use App\Http\Resources\SeriesCollection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Collection extends Model
{

    use SoftDeletes;

    protected $fillable = ['name', 'user_id'];
    private $rename = ["collectionables.collection_id as pivot_collection_id", "collectionables.collectionable_id as `pivot_collectionable_id", "collectionables.collectionable_type as `pivot_collectionable_type"];


    public function collections(){
        return $this->morphToMany('App\Collection', 'collectionable');
    }

    public function comics(){
        return $this->morphedByMany('App\Comic', 'collectionable');
    }

    public function directories(){
        return $this->morphedByMany('App\Directory', 'collectionable');
    }

    public function series(){
        return $this->morphedByMany('App\Series', 'collectionable');

    }

    public function tags(){
        return $this->morphToMany('App\Tag', 'taggable');
    }

    public function cover() {
        return $this->belongsTo('App\Cover');
    }

    public function coverUrl() {
        if($comic = $this->comics()->first())
            return $comic->cover()->first()->id;
        if($directories = $this->directories()->first())
            return $directories->cover()->first()->id;
        if($series = $this->series()->first())
            return $series->coverUrl();
        if($collection = $this->collections()->first())
            return $collection->coverUrl();
        if($cover = $this->cover()->first())
            return $cover->id;
    }

    public function readableSeries() {
        return DB::table('collectionables')->where([['collection_id',$this->id],['collectionable_type','series']])->whereNotIn('collectionable_id', Series::notReadableIds());
    }

    public function readableComics() {
        return DB::table('collectionables')->where([['collection_id',$this->id],['collectionable_type','comic']])->whereNotIn('collectionable_id',Comic::notReadableIds());
    }

    public function readableDirectories() {
    //    if( is_null(Auth::user())) return $this->directories();
    //    if( Auth::user()->isAdmin()) return $this->directories->withTrashed();
        return DB::table('collectionables')->where([['collection_id',$this->id],['collectionable_type','directory']])->whereNotIn('collectionable_id', Directory::notReadableIds());
    }

    public function readableChildren(){
        $series = $this->readableSeries()->select(['collectionable_id as id', 'collectionable_type as type']);
        $comics = $this->readableComics()->select(['collectionable_id as id', 'collectionable_type as type']);
        $directories = $this->readableDirectories()->select(['collectionable_id as id', 'collectionable_type as type']);
        return $comics->union($series)->union($directories);
    }

    public function children(){
        return (new SeriesCollection($this->series()->get()))->merge(new ComicCollection($this->comics()->get()));
    }

    public function collected(){
        return [
            "collections" => $this->collections()->get(),
            "series" => new SeriesCollection($this->series()->get()),
            "directories" => new DirectoryCollection($this->directories()->get()),
            "comics" => new ComicCollection( $this->comics()->get())
        ];
    }

    public function createdBy(){
        return $this->hasOne('App\User');
    }

    public function users(){
        return $this->morphToMany('App\User', 'readable');
    }

    public function readableBy($user){
        if($user)
        if($rule = $this->users()->withPivot('allow')->select('allow')->find($user->id))
            return $rule->allow == 1;
        return security_settings['default_rule']['value'];
    }

    public static function readable() {
        return Auth::user() ? Auth::user()->readableCollections() : new self;
        //Committato solo per test, scommentarlo
        //if(Auth::user()->isAdmin()) return self::withTrashed();
        return Collection::whereNotIn('id', Collection::notReadableIds());
    }

    public static function notReadable() {
        return self::all()->reject(function($collection){
            return $collection->readableBy(Auth::user());
        });
    }

    public static function notReadableIds() {
        return self::notReadable()->map(function ($collection){
            return $collection->id;
        });
    }
}
