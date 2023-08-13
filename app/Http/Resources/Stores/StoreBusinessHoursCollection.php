<?php

namespace App\Http\Resources\Stores;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class StoreBusinessHoursCollection extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $format = (empty(config('app_settings.time_format.value')) ? config('app_settings.time_format.value') : 'g:i A');

         return [
            "status" => (int) $this->status,
            "days" => (string) $this->days,
            "opening_time" => (integer) $this->opening_time,
            "closing_time" => (integer) $this->closing_time,
            "opening_time_format" => (string) Carbon::createFromTimestamp($this->opening_time)->format($format),
            "closing_time_format" => (string) Carbon::createFromTimestamp($this->closing_time)->format($format)
         ];
    }
}
