<?php

namespace App\Http\Resources\Media;

use Illuminate\Http\Resources\Json\JsonResource;
use Storage;
use Carbon\Carbon;

class MediaCollection extends JsonResource
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
            'store_id' => (integer) $this->store_id,
            'collection_name' => (string) $this->collection_name,
            'name' => (string) $this->name,
            'file_name' => (string) $this->file_name,
            'path' =>  Storage::disk(config('app_settings.filesystem_disk.value'))->url($this->file_name),
            'mime_type' => (string) $this->mime_type,
            'disk' => (string) $this->disk,
            'conversions_disk' => (string) $this->conversions_disk,
            'size' => (integer) $this->size,
            'created_at' => Carbon::parse($this->created_at)->format(config('app_settings.date_format.value')),
            'updated_at' => Carbon::parse($this->updated_at)->format(config('app_settings.date_format.value')),
        ];
    }
}
