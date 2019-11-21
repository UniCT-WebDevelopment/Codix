<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function roles()
    {
        return $this->belongsToMany('App\Role');
    }

    public function groups()
    {
        return $this->belongsToMany('App\Group');
    }

    public function comics(){
        return $this->morphedByMany('App\Comic','readable');
    }

    public function notReadableComics() {

        /**
         * Return the unreadable comics based on Comic's readebleBy() function, recursively and very slow.
         */
        return Comic::whereIn('id', $this->blackComicIds());

        /**
         * Return the unreadable comics based on the readables table, not recursively
         */

        return $this->comics()->wherePivot('allow', false);
    }

    public function blackComicIds() {
        return Comic::select('id')->get()->reject(function($comic){
            return $comic->readableBy($this);
        });
    }

    public function readableComics(){

        // Fa 2 query
        if (security_settings['recursive_rule']['value'])
            return Comic::whereNotIn('id', Comic::notReadableIds());

        // Fa sempre una sola query
        return
            security_settings['default_rule']['value']
            ? Comic::whereNotIn('comics.id', $this->comics()->wherePivot('allow', false)->select('comics.id')->get())
            : $this->comics()->wherePivot('allow', true);

    }

    public function readableSeries() {
        /*
        if($this->isAdmin())
            return Series::withTrashed();
            */
        if (security_settings['recursive_rule']['value'])
            return Series::whereNotIn('id', Series::notReadableIds());
        return security_settings['default_rule']['value']
        ? Series::whereNotIn('id', $this->series()->wherePivot('allow', false)->select('series.id')->get())
        : $this->series()->wherePivot('allow', true);
    }

    public function readableCollections() {
/*
        if($this->isAdmin())
            return Collection::withTrashed();
*/
        // Fa 2 query
        if (security_settings['recursive_rule']['value'])
            return Collection::whereNotIn('id', Collection::notReadableIds());

        // Fa sempre una sola query
        return
            security_settings['default_rule']['value']
            ? Comic::whereNotIn('id', $this->comics()->wherePivot('allow', false)->select('comics.id')->get())
            : $this->comics()->wherePivot('allow', true);

    }

    public function readableTags() {
        return security_settings['default_rule']['value']
        ? Tags::whereNotIn('id', $this->tags()->wherePivot('allow', false)->select('tags.id')->get())
        : $this->tags()->wherePivot('allow', true);
    }

    public function directories(){
        return $this->morphedByMany('App\Directory','readable');
    }

    public function series(){
        return $this->morphedByMany('App\Series','readable');
    }

    public function collections(){
        return $this->morphedByMany('App\Collection','readable');
    }

    public function tags() {
        return $this->morphedByMany('App\Tag', 'readable');
    }

    public function rules(){
       // return $this->comics()->withPivot(['id','allow']);
        return $this->comics()->withPivot(['id','allow'])->select(['title', 'allow','readables.id as rule_id','readables.readable_type as resource_type','readables.readable_id as id'])
                ->union(
                    $this->directories()->withPivot(['id','allow'])->select(['name as title', 'allow','readables.id as rule_id','readables.readable_type as resource_type','readables.readable_id as id','readables.user_id as pivot_user_id', 'readables.readable_id as pivot_readable_id', 'readables.readable_type as pivot_readable_type', 'readables.id as pivot_id', 'readables.allow as pivot_allow'])
                )->union(
                    $this->series()->withPivot(['id','allow'])->select(['name as title', 'allow','readables.id as rule_id','readables.readable_type as resource_type','readables.readable_id as id','readables.user_id as pivot_user_id', 'readables.readable_id as pivot_readable_id', 'readables.readable_type as pivot_readable_type', 'readables.id as pivot_id', 'readables.allow as pivot_allow'])
                )->union(
                    $this->collections()->withPivot(['id','allow'])->select(['name as title', 'allow','readables.id as rule_id','readables.readable_type as resource_type','readables.readable_id as id','readables.user_id as pivot_user_id', 'readables.readable_id as pivot_readable_id', 'readables.readable_type as pivot_readable_type', 'readables.id as pivot_id', 'readables.allow as pivot_allow'])
                );

        return [
            'comics' => $this->comics()->withPivot(['id','allow'])->select(['readables.id AS rule_id','readable_type AS resource_type','readable_id AS resource_id','title', 'allow',])->get(),
            'directories' => $this->directories()->withPivot(['id','allow'])->select(['name'])->get(),
            'series' => $this->series()->withPivot(['id','allow'])->select([ 'name'])->get(),
            'collections' => $this->collections()->withPivot(['id','allow'])->select(['name'])->get(),
        ];
    }

    public function isAdmin()
    {
        return $this->name == 'admin';
    }
}
