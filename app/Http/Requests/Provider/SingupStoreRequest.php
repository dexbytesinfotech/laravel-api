<?php

namespace App\Http\Requests\Provider;

use Illuminate\Foundation\Http\FormRequest;

class SingupStoreRequest extends FormRequest
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
            "address_line_1.required" => __('validation/provider.The address line 1 field is required'),
            "landmark.required" => __('validation/provider.The landmark field is required'),
            "latitude.required" => __('validation/provider.The latitude field is required'),
            "longitude.required" => __('validation/provider.The longitude field is required'),
            "country.required" => __('validation/provider.The name field is required'),
            "city.required" => __('validation/provider.The name field is required'),
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
        ];
    }

}
