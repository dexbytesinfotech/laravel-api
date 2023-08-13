<?php

namespace App\Http\Resources\Customer;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class AddressResource extends JsonResource
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
            'id' => (integer) $this->id,
            'user_id' => (integer) $this->user_id,
            'line_1_number_building' => (string) $this->line_1_number_building,
            'line_2_number_street' => (string) $this->line_2_number_street,
            'line_3_area_locality' => (string) $this->line_3_area_locality,
            'city' => (string) $this->city ,
            'state' => (string) $this->state ,
            'country' => (string) $this->country,
            'zip_postcode' => (integer) $this->zip_postcode ,
            'first_name' => (string) $this->first_name,
            'last_name' => (string) $this->last_name,
            'phone' => (string) $this->phone,
            'addrees_type' => (string) $this->addrees_type,
            'latitude' =>  floatval($this->latitude ? $this->latitude: ""),
            'longitude' => floatval($this->longitude ? $this->longitude: ""),
            'apartment_number' => (string) $this->apartment_number,
            'floor_number' => (string) $this->floor_number,
            'address' => (string) $this->address,
            'additional_information' => (string) $this->additional_information,
            'is_primary' => (integer) $this->is_primary,
            'created_at' => Carbon::parse($this->created_at)->format(config('app_settings.date_format.value')),
        ];
    }
}
