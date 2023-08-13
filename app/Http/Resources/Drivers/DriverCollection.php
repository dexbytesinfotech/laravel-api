<?php

namespace App\Http\Resources\Drivers;

use Illuminate\Http\Resources\Json\JsonResource;

class DriverCollection extends JsonResource
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
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'address' => $this->address,
            'email' => $this->email,
            'date_of_birth' => $this->date_of_birth,
            'driver_license' => $this->driver_license,
            'driver_license_expiry_date' => $this->driver_license_expiry_date,
            'phone_number' => $this->phone_number,
            'city' => $this->city,
            'state' => $this->state,
            'ride_platform' => $this->ride_platform,
            'account_status' =>$this->account_status,
            'driver_photo' => $this->driver_photo,
            'trial_account' => $this->trial_account,
            'stripe_customer_id' => $this->stripe_customer_id,
            'damoov_token' => $this->damoov_token,
            'user_id' => $this->user_id,
        ];
    }
}
