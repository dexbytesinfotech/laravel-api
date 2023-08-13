<?php

namespace App\Http\Requests\Apis;

use Illuminate\Foundation\Http\FormRequest;

class StoreApiKeyRequest extends FormRequest
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
            'key' => 'required|string',
            'value' => 'required|string',
            'provider_id' => 'required|integer|exists:App\Models\Apis\ApiProvider,id',
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
            'provider_id' => $this->provider_id,
            'key' => $this->key,
            'value' => $this->value,
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
            "key" => trans('validation/apiprovider.The key field is required'),
            "value" => trans('validation/apiprovider.The value field is required'),
            "provider_id" => trans('validation/apiprovider.The provider id field is required'),
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
            'provider_id' => 'trim',
            'key' => 'trim',
            'value' => 'trim',
        ];
    }

}
