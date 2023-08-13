<?php

namespace App\Http\Requests\Slider;

use Illuminate\Foundation\Http\FormRequest;

class SliderUpdateRequest extends FormRequest
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
            'description' => 'nullable',
            'status' => 'nullable|integer|between:0,1',
            'is_default'=> 'nullable|integer|between:0,1',
            'start_date_time' => 'nullable|date|after_or_equal:'.date('Y-m-d H:i:s').'|before:end_date_time|date_format:Y-m-d H:i:s',
            'end_date_time' => 'nullable|date|after:start_date_time|date_format:Y-m-d H:i:s'
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
            'code' => $this->code,
            'description' => $this->description ? $this->description: null,
            'status' => $this->status ? $this->status: 0,
            'is_default'=> $this->is_default ? $this->is_default:0,
            'start_date_time' => $this->start_date_time ? $this->start_date_time: null ,
            'end_date_time' => $this->end_date_time ? $this->end_date_time: null ,
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

            "name.required" => __('validation/slider.The name field is required'),
           
        ];
    }
}
