<?php

namespace App\Http\Requests\Provider;

use Illuminate\Foundation\Http\FormRequest;

class SingupProviderRequest extends FormRequest
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
            'name'                           => 'required|max:50|unique:App\Models\Stores\StoreTranslation,name',
            'store_type'                => 'required|string',
            'number_of_branch'               => 'required|integer',
            'phone'                          => 'required|numeric|digits_between:8,12|unique:App\Models\Stores\Store,phone',
            'country_code'                   => 'required|numeric|digits_between:1,4',
            'commercial_records_certificate' => 'required|string|exists:App\Models\Media\Media,file_name',
            'address_line_1'                 => 'required|string',
            'landmark'                       => 'required|string',
            'latitude'                       => 'required|between:-90,90',
            'longitude'                      => 'required|between:-90,90',
            'country'                        => 'required|string',
            'city'                           => 'required|string',
            'provider_email'                 => 'required|email|unique:App\Models\User,email',
            'provider_name'                  => 'required|string',
            'provider_country_code'          => 'required|numeric|digits_between:1,4',
            'provider_phone'                 => 'required|numeric|digits_between:8,12',
            'user_name'          => 'required|unique:App\Models\User,user_name',
            'date_of_birth'      => 'required|',
            'nationality'        => 'required|string',
            'restaurent_address' => 'required',
            'photo_id_proof'     => 'required|exists:App\Models\Media\Media,file_name',
            'profile_photo'      => 'required|exists:App\Models\Media\Media,file_name'
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
            'number_of_branch' => (integer) !empty($this->number_of_branch) ? $this->number_of_branch : 1,
            'phone' => (integer) $this->country_code.''.$this->phone,
            'provider_phone' => (integer) $this->provider_country_code.''.$this->provider_phone,
            'status' => (integer) 0
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
            "store_type.required" => __('validation/provider.The restaurant type field is required'),
            "number_of_branch.required" => __('validation/provider.The number of branch field is required'),
            "phone.required" => __('validation/provider.The phone field is required'),
            "country_code.required" => __('validation/provider.The country code field is required'),
            "commercial_records_certificate.required" => __('validation/provider.The commercial records certificate field is required'),
            "address_line_1.required" => __('validation/provider.The address_line_1 field is required'),
            "landmark.required" => __('validation/provider.The landmark field is required'),
            "latitude.required" => __('validation/provider.The latitude field is required'),
            "longitude.required" => __('validation/provider.The longitude field is required'),
            "country.required" => __('validation/provider.The country field is required'),
            "city.required" => __('validation/provider.The city field is required'),
            "provider_email.required" => __('validation/provider.The provider email field is required'),
            "provider_name.required" => __('validation/provider.The provider name field is required'),
            "provider_country_code.required" => __('validation/provider.The provider country code field is required'),
            "provider_phone.required" => __('validation/provider.The provider phone field is required'),
            "user_name.required" => __('validation/provider.The user name field is required'),
            "date_of_birth.required" => __('validation/provider.The date of birth field is required'),
            "nationality.required" => __('validation/provider.The nationality field is required'),
            "restaurent_address.required" => __('validation/provider.The restaurent address field is required'),
            "photo_id_proof.required" => __('validation/provider.The photo id proof field is required'),
            "profile_photo.required" => __('validation/provider.The profile photo field is required'),

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
            'provider_name' => 'trim',
            'provider_phone' => 'trim'
        ];
    }

}
