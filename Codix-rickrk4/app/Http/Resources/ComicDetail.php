<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ComicDetail extends JsonResource
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
            'url' => "c/$this->id",
            'title' => $this->title,
            'coverUrl' => "g/$this->cover_id",
            'type' => 'c',
            'id' => $this->id,
            'trashed' => $this->trashed(),
            'user_tags' => json_decode(!is_null($this->user_tags) ? $this->user_tags : null),
            'tags' => new TagCollection($this->tags()->get()),
            'authors' => new AuthorCollection($this->authors()->get()),
            'publishers' => new PublisherCollection($this->publishers()->get())
        ];
        return parent::toArray($request);
    }
}
