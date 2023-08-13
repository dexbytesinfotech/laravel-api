<?php

namespace App\Http\Resources\Users;

use App\Http\Resources\Roles\UserRoleResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;
class UserResource extends JsonResource
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
            'id' => (int)$this->id,
            'name' =>(string)$this->name,
            'email' => (string)$this->email,
            'phone' => (string)$this->phone,
            'role_id' => UserRoleResource::collection($this->roles) ,
            'email_verified_at' =>(string) $this->email_verified_at,
            'phone_verified_at' => $this->phone_verified_at,
            'remember_token'=>(string) $this->remember_token,
            'last_login' =>(string) $this->last_login,
            'global_notifications' => (Boolean)$this->global_notifications,
            'default_lang' =>(string) trim($this->default_lang),
            'device' => $this->device,
            'message_count' => (integer) $this->total_message,
            'notification_count' => (integer) $this->total_notification('customer'),
            'status' => $this->status,
            'display_message' => $this->message($this),
            'is_block_account' => (boolean) ($this->status == 0) ? true : false,
            'created_at' => Carbon::parse($this->created_at)->format(config('app_settings.date_format.value')),
            'updated_at' => Carbon::parse($this->updated_at)->format(config('app_settings.date_format.value')),
        ];
    }


    protected function message($user)
    {
        $message = "";
        switch ($user->status) {
            case 0:
                $message = 'Your account has been disabled, Please contact to administrator';
                break;
            default:
                break;
        }

        return $message;
    }
}
