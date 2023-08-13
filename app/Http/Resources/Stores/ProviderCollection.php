<?php

namespace App\Http\Resources\Stores;

use App\Http\Resources\Roles\UserPermissionRoleResource;
use App\Http\Resources\Roles\UserRoleResource;
use Illuminate\Http\Resources\Json\JsonResource;
use phpDocumentor\Reflection\Types\Boolean;
use Carbon\Carbon;

class ProviderCollection extends JsonResource
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
            'email' => $this->email,
            'phone' => $this->phone,
            'created_at' => Carbon::parse($this->created_at)->format(config('app_settings.date_format.value')),
            'updated_at' => Carbon::parse($this->updated_at)->format(config('app_settings.date_format.value')),
        ];
    }
}
