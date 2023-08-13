<?php

namespace App\Http\Resources\Customer;

use App\Http\Resources\Roles\RoleCollection;
use App\Http\Resources\Roles\RoleResource;
use App\Http\Resources\Roles\UserPermissionRoleResource;
use App\Http\Resources\Roles\UserRoleCollection;
use App\Http\Resources\Users\CartCollection;
use App\Http\Resources\Users\JoinCartCollection;
use App\Http\Resources\Roles\PublicRoleResource;
use Illuminate\Http\Resources\Json\JsonResource;
use phpDocumentor\Reflection\Types\Boolean;
use App\Constants\OrderReviewTypes;
use Carbon\Carbon;
use Storage;

class ProfileResource extends JsonResource
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
            'first_name' => (string) $this->first_name,
            'last_name' => (string) $this->last_name,
            'email' => (string)$this->email,
            'phone' => (string)$this->phone,
            'role_id' => PublicRoleResource::collection($this->roles) ,
            'email_verified_at' =>(string) $this->email_verified_at,
            'phone_verified_at' => $this->phone_verified_at,
            'remember_token'=>(string) $this->remember_token,
            'last_login' =>(string) $this->last_login,
            'global_notifications' => (Boolean)$this->global_notifications,
            'default_lang' =>(string) trim($this->default_lang),
            'device' => $this->device,
            'profile_photo' =>  !empty($this->profile_photo) ? Storage::disk(config('app_settings.filesystem_disk.value'))->url($this->profile_photo) : Storage::disk(config('app_settings.filesystem_disk.value'))->url(config('app_settings.customer_image.value')),
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
