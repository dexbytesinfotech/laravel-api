<?php

namespace App\Http\Resources\Stores;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class StoreAddressResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => (int) $this->id,
            'address_line_1' => (string) $this->address_line_1,
            'address_line_2' => (string) $this->address_line_2,
            'address_line_3' => (string) $this->address_line_3,
            'landmark' => (string) $this->landmark,
            'city' => (string) $this->city,
            'state' => (string) $this->state,
            'country' => (string) $this->country,
            'zip_post_code' => (int) $this->zip_post_code,
            'is_primary' => (int) $this->is_primary,
            'addrees_type' => (string) $this->addrees_type,
            'latitude' => (float) $this->latitude,
            'longitude' => (float) $this->longitude,
            'created_at' => Carbon::parse($this->created_at)->format(config('app_settings.date_format.value')),
            'updated_at' => Carbon::parse($this->updated_at)->format(config('app_settings.date_format.value')),
        ];
    }
}
