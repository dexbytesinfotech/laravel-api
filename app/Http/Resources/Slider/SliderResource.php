<?php

namespace App\Http\Resources\Slider;

use Illuminate\Http\Resources\Json\JsonResource;
use Storage;
use App\Http\Resources\Slider\SliderImageCollection;
use Carbon\Carbon;


class SliderResource extends JsonResource
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
            'name' => $this->name,
            'code' => $this->code,
            'description' => (string) $this->description,
            'status' => (integer) $this->status,
            'is_default' =>(boolean) $this->is_default ,
            'start_date_time' => Carbon::parse($this->start_date_time)->format(config('app_settings.date_format.value')),
            'end_date_time' => Carbon::parse($this->end_date_time)->format(config('app_settings.date_format.value')),
            'image'=>  SliderImageCollection::collection($this->sliderImage),
            'created_at' => Carbon::parse($this->created_at)->format(config('app_settings.date_format.value')),
            'updated_at' => Carbon::parse($this->updated_at)->format(config('app_settings.date_format.value')),
        ];
    }
}
