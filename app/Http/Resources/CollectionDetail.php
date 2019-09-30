<?php

namespace App\Http\Resources;

use App\Http\Resources\Comic;
use \App\Comic as AppComic;

use App\Http\Resources\Series;
use App\Series as AppSeries;

use App\Http\Resources\Directory;
use App\Directory as AppDirectory;

use App\Http\Resources\Collection;
use App\Collection as AppCollection;

use Illuminate\Http\Resources\Json\ResourceCollection;

class CollectionDetail extends ResourceCollection
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        //return parent::toArray($request);
        return $this->map(function($child)
        {
            switch ($child->type) 
            {
                case 'comic':
                    return new Comic(AppComic::find($child->id));
                case 'directory':
                    return new Directory(AppDirectory::find($child->id));
                case 'series':
                    return new Series(AppSeries::find($child->id));
                case 'collection':
                    return new Collection(AppCollection::find($child->id));
                default:
                    return $child;
            }
        });
            
    }
}
