<?php

namespace App\Http\Requests\Worlds;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCitiRequest extends FormRequest
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
            'name' => 'required|unique:App\Models\Worlds\Cities,name',
            'country_id' => 'required|integer|exist:App\Models\Worlds\Country,id',
            'state_id' => 'required|integer|exist:App\Models\Worlds\State,id',
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
            'country_id' => $this->country_id,
            'state_id' => $this->state_id,
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
            "country_id.required" => __('validation/worlds.The country id field is required'),
            "state_id.required" => __('validation/worlds.The state id field is required'),
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
