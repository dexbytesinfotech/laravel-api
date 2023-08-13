<?php

namespace App\Http\Resources\Apis;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class ApiKeyResource extends JsonResource
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
            'id' => (integer)$this->id,
            'provider_id' => (integer)$this->provider_id,
            'key' => ($this->key) ? $this->key : '',
            'value' => ($this->value) ? $this->value : '',
            'created_at' => Carbon::parse($this->created_at)->format(config('app_settings.date_format.value')),
            'updated_at' => Carbon::parse($this->updated_at)->format(config('app_settings.date_format.value')),
            ];
    }
}
