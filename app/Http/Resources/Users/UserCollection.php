<?php

namespace App\Http\Resources\Users;

use App\Http\Resources\Roles\UserPermissionRoleResource;
use App\Http\Resources\Roles\UserRoleResource;
use Illuminate\Http\Resources\Json\JsonResource;
use phpDocumentor\Reflection\Types\Boolean;
use Carbon\Carbon;

class UserCollection extends JsonResource
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
            'name' =>$this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'country_code' => $this->country_code,
            'roles' => UserRoleResource::collection($this->roles) ,
            'email_verified_at' => $this->email_verified_at,
            'phone_verified_at' => $this->phone_verified_at,
            'last_login' => $this->last_login,
            'global_notifications' => (Boolean)$this->global_notifications,
            'default_lang' => trim($this->default_lang),
            'message_count' => (integer) $this->total_message,
            'notification_count' => (integer) $this->total_notification('customer'),
            'created_at' => Carbon::parse($this->created_at)->format(config('app_settings.date_format.value')),
            'updated_at' => Carbon::parse($this->updated_at)->format(config('app_settings.date_format.value')),
        ];
    }
}
