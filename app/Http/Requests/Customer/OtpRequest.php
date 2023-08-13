<?php

namespace App\Http\Requests\Customer;

use App\Http\Requests\FormRequest;

class OtpRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'otp' => 'required|regex:/[0-9]{4}/|digits:4'
        ];
    }


      /**
     * Get the validation messages that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function messages()
    {
        return [
            'otp.required'   => __('validation/customer.The otp field is required'),
            'otp.digits'   => __('validation/customer.The otp must be 4 digits'),

        ];
    }
}
