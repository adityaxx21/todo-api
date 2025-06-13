<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateTodoListRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title' => ['nullable', 'string'],
            'assignee' => ['nullable', 'string'],
            'due_date' => ['nullable', 'date', function ($attribute, $value, $fail) {
                if (Carbon::parse($value)->isPast()) {
                    $fail('The due date cannot be in the past.');
                }
            }],
            'status' => ['nullable', Rule::in(['pending', 'open'])],
            'priority' => ['nullable', Rule::in(['low', 'medium', 'high'])],
            'time_tracked' => ['nullable', 'numeric'],
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'status' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors(),
            ], 422)
        );
    }

}
