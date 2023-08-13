<?php

namespace App\Http\Resources\Users;

use App\Http\Resources\Roles\RoleCollection;
use App\Http\Resources\Roles\RoleResource;
use App\Http\Resources\Roles\UserPermissionRoleResource;
use App\Http\Resources\Roles\UserRoleCollection;
use App\Http\Resources\Roles\UserRoleResource;
use Illuminate\Http\Resources\Json\JsonResource;
use phpDocumentor\Reflection\Types\Boolean;

class LoginResource extends JsonResource
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
            'phone_verified_at' => (string) $this->phone_verified_at,
            'permission_id' => UserPermissionRoleResource::collection($this->permissions),
            'email_verified_at' =>$this->email_verified_at,
            'last_login' => $this->last_login,
            'global_notifications' => (Boolean)$this->global_notifications,
            'default_lang' => trim($this->default_lang),
            'token' => $this->token,
            'message_count' => (integer) $this->total_message,
            'notification_count' => (integer) $this->total_notification('customer'),
        ];
    }
}
