<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Series extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'url' => "s/$this->id",
            'title' => $this->name,
            'coverUrl' => "g/".$this->coverUrl(),
            'type' => 's',
            'id' => $this->id,
            'trashed' => false
        ];

    }
}
