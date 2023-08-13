<?php

namespace App\Http\Resources\Users;

use App\Http\Resources\Roles\RoleCollection;
use App\Http\Resources\Roles\RoleResource;
use App\Http\Resources\Roles\UserPermissionRoleResource;
use App\Http\Resources\Roles\UserRoleCollection;
use App\Http\Resources\Roles\UserRoleResource;
use Illuminate\Http\Resources\Json\JsonResource;
use phpDocumentor\Reflection\Types\Boolean;
use App\Http\Resources\Stores\StoreResource;
use \App\Models\Stores\Store;

class ProviderLoginResource extends JsonResource
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
            'permission_id' => UserPermissionRoleResource::collection($this->permissions),
            'email_verified_at' =>$this->email_verified_at,
            'phone_verified_at' => (string) $this->phone_verified_at,
            'last_login' => $this->last_login,
            'global_notifications' => (Boolean) $this->global_notifications,
            'default_lang' => trim($this->default_lang),
            'store_id' =>  $this->store->store_id,
            'application_status' =>  $this->application_status,
            'store' => new StoreResource(Store::where('id', $this->store->store_id)->first()),
            'token' => $this->token,
            'is_block_account' => (boolean) ($this->application_status != 'approved') ? true : false,
            'display_message' => $this->message($this),
            'message_count' => (integer) $this->total_message,
            'notification_count' => (integer) $this->total_notification('provider'),
        ];
    }

    protected function message($user)
    {
        $message = "";
        switch ($user->application_status) {
            case 'rejected':
                $message = 'Your application has been rejected, Please contact to administrator';
                break;
            case 'waiting':
                $message = 'Your application has been submitted and pending approval by administrator administrator or moderator';
                break;
            case 'suspended':
                $message = 'Your account has been suspended, Please contact to administrator';
                break;
            default:
                $message = '';
                break;
        }

        return $message;
    }


}
