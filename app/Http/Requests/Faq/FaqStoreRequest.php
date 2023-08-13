<?php

namespace App\Http\Requests\Faq;

use App\Http\Requests\FormRequest;

class FaqStoreRequest extends FormRequest
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
            'title' => 'required',
            'descriptions' => 'required|max:100',
            'status' => 'nullable|integer|between:0,1',
            'role_type' => 'nullable',
            'faq_category_id' => 'required|integer|exists:App\Models\Faq\FaqCategory,id'
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
            'title' => $this->title,
            'descriptions' => $this->descriptions,
            'faq_category_id' => $this->faq_category_id ? $this->faq_category_id: null ,
            'role_type' => $this->role_type,
            'status' => $this->status ? $this->status: 0 ,
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
            'title.required'   => __('validation/faq.The title field is required'),
            'descriptions.required'   => __('validation/faq.The descriptions field is required'),
            'descriptions.max'   => __('validation/faq.The descriptions must not be greater than 100 characters'),
            'faq_category_id.required'   => __('validation/faq.The faq category id field is required'),
            'faq_category_id.exists'   => __('validation/faq.The selected faq category id is invalid'),
        ];
    }
}
