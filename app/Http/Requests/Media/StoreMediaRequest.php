<?php

namespace App\Http\Requests\Media;

use Illuminate\Foundation\Http\FormRequest;

class StoreMediaRequest extends FormRequest
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
            'collection_name' => 'required',
            'file' => 'required|image|mimes:jpeg,png,jpg,svg|max:2048',
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
            // Write your code here
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
            "collection_name.required" => __('validation/media.The collection name field is required'),
            "file.required"            => __('validation/media.The file field is required'),
            "file.image"               => __('validation/media.The file must be an image'),
            "file.mimes"               => __('validation/media.The file must be a type of jpeg,png,jpg,svg'),
            "file.max"               => __('validation/media.The file must not be greater than 2048 kilobytes'),
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
