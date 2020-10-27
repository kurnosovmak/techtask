<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Product extends JsonResource
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
            'id' => $this->id,
            'title' => $this->title,
            'price' => $this->price,
            'mini_description' => $this->mini_description,
            'full_description' => $this->full_description,
            'published' => $this->published,
//            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
