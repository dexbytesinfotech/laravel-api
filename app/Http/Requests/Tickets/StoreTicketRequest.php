<?php

namespace App\Http\Requests\Tickets;

use Illuminate\Foundation\Http\FormRequest;

class StoreTicketRequest extends FormRequest
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
            'user_id' => 'nullable|integer|exists:App\Models\User,id',
            'assigned_to_user_id' => 'nullable|integer|exists:App\Models\User,id'
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
            'content' => $this->content ? $this->content : null,
            'status' => $this->status ? $this->status: 'open' ,
            'category_id' => $this->category_id ? $this->category_id : 1, 
            'user_id' => $this->user_id ? $this->user_id : null,
            'assigned_to_user_id' => $this->assigned_to_user_id ? $this->assigned_to_user_id : null
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
        ];
    }

    
}
