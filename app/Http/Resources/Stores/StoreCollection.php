<?php

namespace App\Http\Resources\Stores;

use Illuminate\Http\Resources\Json\JsonResource;
use Storage;
use Carbon\Carbon;

class StoreCollection extends JsonResource
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
            'phone' => (string) $this->phone,
            'email' => (string) $this->email,
            'content' => (string) $this->content,
            'number_of_branch' => (int) $this->number_of_branch,
            'logo_path' => (string) !empty($this->logo_path) ? Storage::disk(config('app_settings.filesystem_disk.value'))->url($this->logo_path) : Storage::disk(config('app_settings.filesystem_disk.value'))->url(config('app_settings.store_logo.value')),
            'background_image_path' => (string)  !empty($this->background_image_path) ? Storage::disk(config('app_settings.filesystem_disk.value'))->url($this->background_image_path) : Storage::disk(config('app_settings.filesystem_disk.value'))->url(config('app_settings.store_background_image.value')),
            'status' => (int)$this->status,
            'application_status' =>  $this->application_status,
            'is_primary' => (int) $this->is_primary,
            'store_address' => new StoreAddressResource($this->storeAddress),
            'business_hours' => StoreBusinessHoursCollection::collection($this->BusinessHour),
            'created_at' => Carbon::parse($this->created_at)->format(config('app_settings.date_format.value')),
            'updated_at' => Carbon::parse($this->updated_at)->format(config('app_settings.date_format.value')),
        ];
    }
}
