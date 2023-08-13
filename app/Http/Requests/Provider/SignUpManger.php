<?php

namespace App\Http\Requests\Provider;

use Illuminate\Foundation\Http\FormRequest;

class SignUpManger extends FormRequest
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
             'name'               => 'required|string',
             'email'              => 'required|email|unique:App\Models\User,email',
             'phone'              => 'required|numeric|digits_between:8,12|unique:App\Models\User,phone',
             'user_name'          => 'required|unique:App\Models\User,user_name',
             'date_of_birth'      => 'required|',
             'nationality'        => 'required|string',
             'restaurent_address' => 'required',
             'photo_id_proof'     => 'required|exists:App\Models\Media\Media,file_name',
             'profile_photo'      => 'required|exists:App\Models\Media\Media,file_name',
             'store_id'           => 'required|integer|exists:App\Models\Stores\Store,id',

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
            "name.required" => __('validation/provider.The name field is required'),
            "email.required" => __('validation/provider.The email field is required'),
            "phone.required" => __('validation/provider.The phone field is required'),
            "user_name.required" => __('validation/provider.The user name field is required'),
            "date_of_birth.required" => __('validation/provider.The date of birth field is required'),
            "nationality.required" => __('validation/provider.The nationality field is required'),
            "restaurent_address.required" => __('validation/provider.The restaurent address field is required'),
            "photo_id_proof.required" => __('validation/provider.The photo id proof field is required'),
            "profile_photo.required" => __('validation/provider.The profile photo field is required'),
            "store_id.required" => __('validation/provider.The store id field is required'),
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
            'phone' => 'trim',
            'user_name' => 'trim'
        ];
    }
}
