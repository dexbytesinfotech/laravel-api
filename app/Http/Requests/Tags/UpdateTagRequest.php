<?php

namespace App\Http\Requests\Tags;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTagRequest extends FormRequest
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
            'title'         => 'required|string|max:50',
            'meta_title'    => 'nullable|string',
            'content'       => 'nullable|string',
            'type'          => 'nullable|max:50',
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
            'title'         => $this->title,
            'meta_title'    => $this->meta_title,
            'content'       => $this->content,
            'type'          => $this->type,
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
            "title.required" => __('validation/tag.The title field is required'),
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
            'title' => 'trim',
            'meta_title' => 'trim',
            'content' => 'trim',
        ];
    }

}
