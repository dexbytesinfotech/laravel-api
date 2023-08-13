<?php
namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;

class FormRequest extends \Illuminate\Foundation\Http\FormRequest
{
    protected function failedValidation(Validator $validator)
    {
        if ($this->expectsJson()) {
            $errors = $validator->errors()->all();
            $response = [
                'success' => false,
                'message' => throw new \Illuminate\Validation\ValidationException($validator),
                'data' => $errors
            ];

            throw new HttpResponseException(
                response()->json($response, 422)
            );
        }


        parent::failedValidation($validator);
    }
}
