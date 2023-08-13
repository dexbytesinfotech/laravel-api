<?php

namespace App\Http\Resources\Customer;

use App\Http\Resources\Roles\UserPermissionRoleResource;
use App\Http\Resources\Roles\UserRoleCollection;
use App\Http\Resources\Roles\UserRoleResource;
use Illuminate\Http\Resources\Json\JsonResource;
use phpDocumentor\Reflection\Types\Boolean;
use Carbon\Carbon;

class LoginCollection extends JsonResource
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
            'first_name' => (string) $this->first_name,
            'last_name' => (string) $this->last_name,
            'email' => (string) $this->email,
            'phone' => (string) $this->phone,
            'country_code' => (string)  $this->country_code,
            'roles' => UserRoleResource::collection($this->roles) ,
            'email_verified_at' => (string) $this->email_verified_at,
            'last_login' => (string)  $this->last_login,
            'global_notifications' => (Boolean) $this->global_notifications,
            'default_lang' => (string) trim($this->default_lang),
            'token' => isset($this->token) ? $this->token : "",
            'created_at' => Carbon::parse($this->created_at)->format(config('app_settings.date_format.value')),
            'updated_at' => Carbon::parse($this->updated_at)->format(config('app_settings.date_format.value')),
        ];

    }
}
