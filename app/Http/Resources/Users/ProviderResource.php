<?php

namespace App\Http\Resources\Users;

use App\Http\Resources\Roles\UserRoleResource;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Stores\StoreResource;
use \App\Models\Stores\Store;
use Carbon\Carbon;

class ProviderResource extends JsonResource
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
            'id' => (int) $this->id,
            'name' =>(string) $this->name,
            'email' => (string) $this->email,
            'phone' => (string) $this->phone,
            'role_id' => UserRoleResource::collection($this->roles) ,
            'email_verified_at' =>(string) $this->email_verified_at,
            'phone_verified_at' => (string) $this->phone_verified_at,
            'remember_token'=>(string) $this->remember_token,
            'last_login' =>(string) $this->last_login,
            'global_notifications' => (Boolean) $this->global_notifications,
            'store_id' => $this->store->store_id,
            'store' => new StoreResource(Store::where('id', $this->store->store_id)->first()),
            'default_lang' => (string) trim($this->default_lang),
            'status' => $this->status,
            'display_message' => $this->message($this),
            'is_block_account' => (boolean) ($this->application_status != 'approved' || $this->status == 0) ? true : false,
            'message_count' => (integer) $this->total_message,
            'notification_count' => (integer) $this->total_notification('provider'),
            'created_at' => Carbon::parse($this->created_at)->format(config('app_settings.date_format.value')),
            'updated_at' => Carbon::parse($this->updated_at)->format(config('app_settings.date_format.value')),
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
                $message = 'Your application has been submitted and pending approval by administrator or moderator';
                break;
            case 'suspended':
                $message = 'Your account has been suspended, Please contact to administrator';
                break;
            default:
                break;
        }

        if(!$user->status && $message == ''){
            $message = 'Your account has been disabled, Please contact to administrator';
        }

        return $message;
    }



}
