<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreTodoListRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title' => ['required', 'string'],
            'assignee' => ['nullable', 'string'],
            'time_tracked' => ['integer'],
            'due_date' => ['required', 'date', function ($attribute, $value, $fail) {
                try {
                    if (Carbon::parse($value)->isPast()) {
                        $fail('The due date cannot be in the past.');
                    }
                } catch (\Throwable $th) {
                    $fail('The due date must be a valid date.');
                }

            }],
            'status' => ['sometimes', Rule::in(['pending', 'open', 'completed', 'in_progress'])],
            'priority' => ['required', Rule::in(['low', 'medium', 'high'])],
        ];
    }

    public function messages()
    {
        return [
            'due_date.required' => 'The due date is required.',
            'due_date.date' => 'The due date must be a valid date.',
            'due_date.after_or_equal' => 'The due date cannot be in the past.',
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

    protected function prepareForValidation()
    {
        if (!$this->has('status')) {
            $this->merge(['status' => 'pending']);
        }
    }
}
