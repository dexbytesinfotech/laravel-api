<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;

class AddressStoreRequest extends FormRequest
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
            'line_1_number_building' => 'nullable',
            'line_2_number_street' => 'required',
            'line_3_area_locality' => 'nullable',
            'city' => 'nullable',
            'state' => 'nullable',
            'country' => 'nullable',
            'zip_postcode' => 'nullable|numeric',
            'first_name' => 'nullable|max:100',
            'last_name' => 'nullable|max:100',
            'phone' => 'nullable|numeric',
            'addrees_type' => 'required|in:Home,Work,Shop,Other',
            'latitude' => 'required|between:-90,90',
            'longitude' => 'required|between:-180,180',
            'apartment_number' => 'nullable',
            'floor_number' => 'nullable',
            'address' => 'required|min:5',
            'additional_information' => 'nullable',
            'is_primary' => 'nullable|integer|between:0,1',
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
            'is_primary' => $this->is_primary ? $this->is_primary : 0
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
            'line_2_number_street'   => __('validation/customer.The line_2_number_street field is required'),
            'phone'          => __('validation/customer.The phone  must be a number'),
            'addrees_type'   => __('validation/customer.The addrees_type field is required'),
            'latitude'   => __('validation/customer.The latitude field is required'),
            'longitude'   => __('validation/customer.The longitude field is required'),
            'address'   => __('validation/customer.The address field is required'),
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
            'first_name' => 'trim',
            'last_name'  => 'trim',
            'addrees_type' => 'trim',
        ];
    }
}
