<?php

namespace App\Http\Resources\Provider;

use App\Http\Resources\Home\StoreAddressResource;
use Illuminate\Http\Resources\Json\JsonResource;

class SinupStoreResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

       $storeAddress = new StoreAddressResource($this->storeAddress);
        return [
            'id'=>(integer)$this->id,
            'name'=>(string)$this->name,
            'store_type'=>(string)$this->store_type,
            'number_of_branch'=>(integer)$this->number_of_branch,
            'phone'=>(integer)$this->phone,
            'country_code'=>(integer)$this->country_code,
            'commercial_records_certificate'=>(string)$this->storeMetaData,
            'address_line_1' =>(string)$storeAddress->address_line_1,
            'landmark'=> (string)$storeAddress->landmark,
            'latitude'=>(string)$storeAddress->latitude,
            'longitude'=>(string)$this->longitude,
            'country'=>(string)$storeAddress->country,
            'city'=>(string)$storeAddress->city,
            'addrees_type'=>(string)$this->addrees_type ? $this->addrees_type:'shop',
            'is_primary' => (integer)$this->is_primary ? $this->is_primary: 1,

        ];
    }
}
