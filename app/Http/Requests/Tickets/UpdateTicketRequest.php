<?php

namespace App\Http\Requests\Tickets;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTicketRequest extends FormRequest
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
            'content' => 'nullable',
            'status' => 'nullable|string|in:draft,open,inprogress,completed,closed,rejected,hold',
            'category_id' => 'required|integer|exists:App\Models\Tickets\TicketCategory,id',
            'user_id' => 'nullable|integer||exists:App\Models\User,id',
            'assigned_to_user_id' => 'nullable|integer||exists:App\Models\User,id'
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
            "title.required" => __('validation/ticket.The title field is required'),
            "category_id.required" => __('validation/ticket.The category id field is required'),
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
