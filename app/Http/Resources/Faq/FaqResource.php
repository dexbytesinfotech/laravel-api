<?php

namespace App\Http\Resources\Faq;

use Illuminate\Http\Resources\Json\JsonResource;

class FaqResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'descriptions' => $this->descriptions,
            'category_id' =>  ($this->category) ? $this->category->id: 0,
            'category_name' => ($this->category) ? $this->category->name: '',
            'status' => (integer) $this->status,
            'role_type' =>$this->role_type,
        ];
    }
}
