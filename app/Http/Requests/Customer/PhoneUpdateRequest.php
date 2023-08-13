<?php

namespace App\Http\Requests\Customer;

use App\Http\Requests\FormRequest;

class PhoneUpdateRequest extends FormRequest
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
            'mobile' => 'required|numeric|digits_between:9,11',
            'country_code' => 'required|numeric|digits_between:2,3',
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
            'mobile.required'   => __('validation/customer.The mobile field is required'),
            'mobile.numeric'   => __('validation/customer.The mobile must be a number'),
            'mobile.digits_between'   => __('validation/customer.The mobile must be between 9 and 11 digits'),
            'country_code.required'   => __('validation/customer.The country_code field is required'),
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
            'mobile' => 'trim'
        ];
    }


}
