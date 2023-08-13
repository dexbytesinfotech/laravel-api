<?php

namespace App\Http\Requests\Apis;

use Illuminate\Foundation\Http\FormRequest;

class StoreApiProviderRequest extends FormRequest
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
            'module' => 'required|max:11',
            'code' => 'required|max:30',
            'name' => 'required|max:60',
            'icon' => 'nullable|max:50',
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
            'module' => $this->module,
            'code' => $this->code,
            'name' => $this->name,
            'icon' => $this->icon,
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
            "module" => trans('validation/apiprovider.The module field is required'),
            "code" => trans('validation/apiprovider.The code field is required'),
            "name" => trans('validation/apiprovider.The name field is required'),
            "icon" => trans('validation/apiprovider.The icon field is required'),
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
            'code' => 'trim',
            'name' => 'trim',
            'icon' => 'trim',
        ];
    }
}
