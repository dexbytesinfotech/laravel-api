<?php

namespace App\Http\Resources\Worlds;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class CitiResource extends JsonResource
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
            'name' => $this->name,
            'country_id' => $this->country_id,
            'state_id' => $this->state_id,
            'order' => $this->order,
            'is_default' => (Boolean)$this->is_default,
            'status'    => (integer)$this->status,
            'created_at' => Carbon::parse($this->created_at)->format(config('app_settings.date_format.value')),
            'updated_at' => Carbon::parse($this->updated_at)->format(config('app_settings.date_format.value')),
        ];
    }
}
