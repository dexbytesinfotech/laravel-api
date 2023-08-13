<?php

namespace App\Http\Resources\Apis;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class ApiProviderCollection extends JsonResource
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
            'module' => ($this->module) ? $this->module : '',
            'code' =>  ($this->code) ? $this->code : '',
            'name' => ($this->name) ? $this->name : '',
            'icon' => ($this->icon) ? $this->icon : '',
            'created_at' => Carbon::parse($this->created_at)->format(config('app_settings.date_format.value')),
            'updated_at' => Carbon::parse($this->updated_at)->format(config('app_settings.date_format.value')),
        ];
    }
}
