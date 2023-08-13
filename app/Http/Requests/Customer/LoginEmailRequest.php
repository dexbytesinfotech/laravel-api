<?php

namespace App\Http\Requests\Customer;

use App\Http\Requests\FormRequest;

class LoginEmailRequest extends FormRequest
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
            'email' => 'email|required',
            'password' => 'required'
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
            'email.required'   => __('validation/customer.The email field is required'),
            'email.email'   => __('validation/customer.The address field is required'),
            'password.required'   => __('validation/customer.The address field is required'),
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
            'email' => 'trim|lowercase'
        ];
    }


}
