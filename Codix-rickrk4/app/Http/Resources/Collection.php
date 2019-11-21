<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;


class Collection extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return[
            'id' => $this->id,
            'url' => "cl/$this->id",
            'title' => $this->name,
            'type' => 'cl',
            'coverUrl' => "g/".$this->coverUrl(),
            'trashed' => false
        ];
        return parent::toArray($request);
    }
}
