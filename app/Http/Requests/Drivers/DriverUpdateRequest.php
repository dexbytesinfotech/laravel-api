<?php

namespace App\Http\Requests\Drivers;

use Illuminate\Foundation\Http\FormRequest;

class DriverUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'first_name' => 'required|max:100|min:3|string',
            'last_name' => 'required|max:100|min:3|string',
            'address' => 'nullable|max:100|min:3',
            'email' => 'required|email|string|max:255',
            'date_of_birth' => 'required|date',
            'driver_license' => 'required|max:100|min:3',
            'driver_license_expiry_date' => 'nullable|date',
            'phone_number' => 'required|numeric|digits_between:9,11',
            'city' => 'nullable|string',
            'state' => 'nullable|string',
            'ride_platform' => 'nullable|string',
            'account_status' => 'required',
            'driver_photo' => 'required|max:100',
            'trial_account' => 'required|integer|between:0,1',
            'stripe_customer_id' => 'nullable',
            'damoov_token' => 'nullable',
            'user_id' => 'nullable',
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge([
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
        ]);
    }


    /**
     * Get the validation messages that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function messages()
    {
        return [
            "first_name.required" => __('validation/driver.The first name field is required'),
            "last_name.required" => __('validation/driver.The last name field is required'),
            "email.required" => __('validation/driver.The email field is required'),
            "date_of_birth.required" => __('validation/driver.The date of birth field is required'),
            "driver_license.required" => __('validation/driver.The driver license field is required'),
            "phone_number.required" => __('validation/driver.The phone field is required'),
        ];
    }

}
