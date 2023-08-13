<?php

namespace App\Http\Requests\Stores;

use Illuminate\Foundation\Http\FormRequest;

class StoreStoreRequest extends FormRequest
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
            'name'                  => 'required|max:50',
            'descriptions'          => 'nullable|string',
            'phone'                 => 'required|numeric|digits_between:9,11',
            'email'                 => 'required|email|string',
            'number_of_branch'      => 'nullable|integer',
            'content'               => 'nullable|string',
            'logo_path'             => 'nullable|exists:App\Models\Media\Media,file_name',
            'background_image_path' => 'nullable|exists:App\Models\Media\Media,file_name',
            'status'                => 'nullable|integer',
            'address_line_1'        => 'required|string',
            'landmark'              => 'required|string',
            'city'                  => 'nullable|string',
            'state'                 => 'nullable|integer|between:0,1',
            'country'               => 'required|string',
            'zip_post_code'         => 'required|integer',
            'latitude'              => 'required|between:-90,90',
            'longitude'             => 'required|between:-90,90',
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
            "name.required" => __('validation/store.The name field is required'),
            "phone.required" => __('validation/store.The phone field is required'),
            "email.required" => __('validation/store.The email field is required'),
            "address_line_1.required" => __('validation/store.The address line 1 field is required'),
            "landmark.required" => __('validation/store.The landmark field is required'),
            "country.required" => __('validation/store.The country field is required'),
            "zip_post_code.required" => __('validation/store.The zip post code field is required'),
            "latitude.required" => __('validation/store.The latitude field is required'),
            "longitude.required" => __('validation/store.The longitude field is required'),
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
             // Write your code here
        ];
    }

}
