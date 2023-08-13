<?php

namespace App\Http\Resources\Slider;

use Illuminate\Http\Resources\Json\JsonResource;
use Storage;
use Carbon\Carbon;
class SliderImageResource extends JsonResource
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
            'slider_id' => (integer) $this->slider_id,
            'title' => (string)$this->title,
            'descriptions' => (string)$this->descriptions,
            'action_values' => (string)$this->action_values,
            'image_path' => (string) Storage::disk(config('app_settings.filesystem_disk.value'))->url($this->image),
            'status' => (boolean) $this->status,
            'start_date_time' => Carbon::parse($this->start_date_time)->format(config('app_settings.date_format.value')),
            'end_date_time' => Carbon::parse($this->end_date_time)->format(config('app_settings.date_format.value')),
            'created_at' => Carbon::parse($this->created_at)->format(config('app_settings.date_format.value')),
            'updated_at' => Carbon::parse($this->updated_at)->format(config('app_settings.date_format.value')),
        ];
    }
}
