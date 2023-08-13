<?php

namespace App\Http\Requests\Pages;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePageRequest extends FormRequest
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
            'title' => 'required|string',
            'content' => 'required',
            'status' => 'in:draft,published,unpublished',
            'post_type' => 'in:post,page',
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
            'status'=> $this->status ? $this->status:'draft',
            'post_type'=>  $this->post_type ? $this->post_type:'page' ,
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
            "title.required" => __('validation/page.The title field is required'), 
            "title.string" => __('validation/page.The title must be a string'), 
            "content.required" => __('validation/page.The content field is required'), 
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
        ];
    }
    
}
