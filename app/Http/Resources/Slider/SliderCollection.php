<?php

namespace App\Http\Resources\Slider;

use Illuminate\Http\Resources\Json\JsonResource;
use Storage;
use Carbon\Carbon;
class SliderCollection extends JsonResource
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
            'id' => (integer) $this->id,
            'name' => $this->name,
            'code' => $this->code,
            'description' => (string) $this->description,
            'status' => (integer) $this->status,
            'is_default' =>(boolean) $this->is_default,
            'start_date_time' => (string) $this->start_date_time,
            'end_date_time' => (string) $this->end_date_time,
            'created_at' => Carbon::parse($this->created_at)->format(config('app_settings.date_format.value')),
            'updated_at' => Carbon::parse($this->updated_at)->format(config('app_settings.date_format.value')),
        ];
    }
}
