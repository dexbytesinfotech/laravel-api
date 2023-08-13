<?php

namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
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
            'name' => 'required',
            'email' => 'required|email|unique:App\Models\User,email',
            'phone' => 'required|unique:App\Models\User,phone|numeric|digits_between:10,13',
            'country_code' => 'required|numeric|digits_between:2,4',
            'password' => 'required',
            'role_id' => 'required|exists:Spatie\Permission\Models\Role,id'
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
             'name' => $this->name,
             'email' => $this->email,
             'phone' => $this->country_code.''.$this->phone,
             'password' => $this->password,
             'role_id' => $this->role_id ? $this->role_id : null,
             'globle_notifications' => $this->globle_notifications ? $this->globle_notifications : 1,
             'default_lang' => $this->default_lang ? $this->default_lang : 'en',
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
            "name.required" => __('validation/user.The name field is required'),
            "email.required" => __('validation/user.The email field is required'),
            "phone.required" => __('validation/user.The phone field is required'),
            "country_code.required" => __('validation/user.The country code field is required'),
            "password.required" => __('validation/user.The password field is required'),
            "role_id.required" => __('validation/user.The role id field is required'),
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
            'name' => 'trim',      
        ];
    }
    
}
