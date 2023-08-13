<?php

namespace App\Http\Requests\Worlds;

use Illuminate\Foundation\Http\FormRequest;

class StoreMobileCityRequest extends FormRequest
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
                 
                'country_id' => 'nullable|integer|exists:countries,id',
                'state_id' =>  'nullable|integer|exists:states,id',
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
            'country_id.integer' => __('worlds.Country-id is must be integer type'),
            'state_id.integer'  => __('worlds.State-id is must be integer type'),
        ];
    }
}
