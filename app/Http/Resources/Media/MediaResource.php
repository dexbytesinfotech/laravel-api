<?php

namespace App\Http\Resources\Media;

use Illuminate\Http\Resources\Json\JsonResource;
use Storage;

class MediaResource extends JsonResource
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
            'collection_name' => $this->collection_name,
            'file_name' => $this->file_name,
            'path' =>  Storage::disk(config('app_settings.filesystem_disk.value'))->url($this->file_name)
        ];
    }
}
