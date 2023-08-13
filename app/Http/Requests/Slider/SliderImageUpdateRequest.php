<?php

namespace App\Http\Requests\Slider;

use Illuminate\Foundation\Http\FormRequest;

class SliderImageUpdateRequest extends FormRequest
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
            'slider_id' => 'required|integer|exists:App\Models\Slider\Slider,id',
            'title' => 'required',
            'image' => ' nullable|exists:App\Models\Media\Media,file_name',
            'status' => 'nullable|integer|between:0,1',
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
            'slider_id' => $this->slider_id,
            'title' => $this->title,
            'code' => $this->code,
            'descriptions' => $this->descriptions ? $this->descriptions: null,
            'action_values' => $this->action_values ? $this->action_values: null,
            'image'  => $this->image ,
            'status' => $this->status ? $this->status: 0,
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
            "slider_id.required" => __('validation/slider.The slider id field is required'),
            "title.required" => __('validation/slider.The title field is required'),

        ];
    }
}
