<?php

namespace App\Http\Requests\Worlds;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCountryRequest extends FormRequest
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
            'country_ios_code' => 'required',
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
            'country_ios_code' => $this->country_ios_code,
            'is_default' =>  ($this->is_default) ?  $this->is_default : 0,
            'status' =>  ($this->status) ?  $this->status : 0,
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
            "name.required" => __('validation/worlds.The name field is required'),
            "country_ios_code.required" => __('validation/worlds.The country ios code field is required'),
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
            'name' => 'trim'
        ];
    }

}
