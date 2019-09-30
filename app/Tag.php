<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    public $fillable = ['name'];
    protected $hidden = ['pivot'];

    public function comics() {
    	return $this->morphedByMany('App\Comic','taggable');
    }

    public function series() {
    	return $this->morphedByMany('App\Series', 'taggable');
    }

    public function collections() {
    	return $this->morphedByMany('App\Collections', 'taggable');
    }

    public function readableBy($user){
    	if($user){
    		if($rule = $user->tags()->withPivot('allow')->find($this->id))
    			return $rule->allow == 1;
    	}
    	return security_settings['default_rule']['value'];
    }
}
