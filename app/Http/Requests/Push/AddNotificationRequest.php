<?php

namespace App\Http\Requests\Push;

use Illuminate\Foundation\Http\FormRequest;

class AddNotificationRequest extends FormRequest
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
            'target_devices' => 'required|in:all,ios,android',
            'title' => 'required|string',
            'text' => 'required|string',
            'with_image' => 'nullable|bool|between:0,1',
            'custom_image' => 'nullable|string|required_if:with_image,1',
            'action_value' => 'nullable|array',
            'send_to' => 'required|in:all,specific_users',
            'user_ids' => 'nullable|required_if:send_to,specific_users',
            'send_at' => 'required|date|date_format:Y-m-d H:i:s',
            'send_until' => 'nullable|date|date_format:Y-m-d H:i:s',
            'app_name' => 'required|in:all,provider,driver,customer',
            'is_silent' => 'nullable|integer|between:0,1',
            'latitude' => 'nullable|between:-90,90',
            'longitude' => 'nullable|between:-180,180',
            'radius' => 'nullable|integer',
            'should_visible' => 'nullable|bool|between:0,1',
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
            'should_visible' => empty($this->should_visible) ? 1 : $this->should_visible,
            'is_silent' => empty($this->is_silent) ? 0 : $this->is_silent,
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
            "target_devices.required" => __('validation/push.The target devices field is required'),
            "title.required" => __('validation/push.The title field is required'),
            "text.required" => __('validation/push.The text field is required'),
            "send_to.required" => __('validation/push.The send to field is required'),
            "send_at.required" => __('validation/push.The send at field is required'),
            "app_name.required" => __('validation/push.The app name field is required'),
        ];
           
    }

}
