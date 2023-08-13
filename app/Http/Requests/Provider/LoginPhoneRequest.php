<?php

namespace App\Http\Requests\Provider;

use App\Http\Requests\FormRequest;

class LoginPhoneRequest extends FormRequest
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
            'phone' => 'required|numeric|digits_between:8,12',
            'country_code' => 'required|numeric|digits_between:1,3',
            'password' => 'required'
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
            'phone' => (integer) $this->country_code.''.$this->phone
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
            "phone.required" => __('validation/provider.The phone field is required'),
            "country_code.required" => __('validation/provider.The country code field is required'),
            "password.required" => __('validation/provider.The password field is required'),

        ];
    }


   /**
     *  Filters to be applied to the input.
     *
     * @return array
     */
    public function filters()
    {
        return [
            'phone' => 'trim'
        ];
    }
}
