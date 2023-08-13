<?php

namespace App\Http\Resources\Worlds;

use Illuminate\Http\Resources\Json\JsonResource;

class CitiByStateResource extends JsonResource
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
            'name' => $this->name,
            'state_id' => $this->state_id,
            'country_id' => $this->country_id,
        ];
    }
}
