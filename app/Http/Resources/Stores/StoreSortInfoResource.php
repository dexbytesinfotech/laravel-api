<?php

namespace App\Http\Resources\Stores;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Stores\StoreAddressResource;
use Storage;

class StoreSortInfoResource extends JsonResource
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
            'name' => (string) $this->name,
            'descriptions' => (string) $this->descriptions,
            'logo_path' => (string) !empty($this->logo_path) ? Storage::disk(config('app_settings.filesystem_disk.value'))->url($this->logo_path) : Storage::disk(config('app_settings.filesystem_disk.value'))->url(config('app_settings.store_logo.value')),
            'store_address' => new StoreAddressResource($this->storeAddress),
        ];
    }
}
