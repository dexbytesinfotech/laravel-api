<?php

namespace App\Http\Requests\Provider;

use Illuminate\Foundation\Http\FormRequest;

class emailOtpRequest extends FormRequest
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
            'otp' => 'required|regex:/[0-9]{4}/|digits:4',
            'email' => 'required|email|unique:users,email',
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
            "otp.required" => __('validation/provider.The otp field is required'),
            "email.required" => __('validation/provider.The email field is required'),
           
        ];
    }
}
