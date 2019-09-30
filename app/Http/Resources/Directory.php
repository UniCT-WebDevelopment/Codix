<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Directory extends JsonResource
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
            'url' => "$this->type/$this->id",
            'title' => $this->name,
            'coverUrl' => "g/$this->cover_id",
            'type' => $this->type,
            'id' => $this->id,
            'parentDir' => $this->directory_id,
        ];
        return parent::toArray($request);
    }
}
