<?php

namespace App\Http\Resources\Worlds;

use Illuminate\Http\Resources\Json\JsonResource;
use phpDocumentor\Reflection\Types\Boolean;
use App\Http\Resources\Worlds\CitiMobileCollection;

class CountryMobileCollection extends JsonResource
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
            'name' =>  (string) $this->name,
            'country_ios_code' => (string) $this->country_ios_code,
            'nationality' =>  (string) $this->nationality,
            'is_default' => (Boolean) $this->is_default,
            'cities' => CitiMobileCollection::collection($this->cities),
        ];
    }
}
