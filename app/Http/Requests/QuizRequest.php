<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QuizRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation()
    {
        if (is_string($this->questions)) {
            $this->merge([
                'questions' => json_decode($this->questions, true),
            ]);
        }

        if ($this->has('status')) {
            $this->merge([
                'status' => filter_var($this->status, FILTER_VALIDATE_BOOLEAN),
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'expire_date' => 'nullable|date',
            'status' => 'nullable',
            'picture' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
            'picture_state' => 'string|in:removed,existing,new',
            'questions' => 'nullable|array',
            'questions.*.id' => 'required|integer',
            'questions.*.question' => 'required|string|max:255',
            'questions.*.type' => 'required|string|in:text,select,radio,checkbox',
            'questions.*.data' => 'nullable|array',
            'questions.*.data.options' => 'nullable|array',
            'questions.*.data.options.*.id' => 'nullable|integer',
            'questions.*.data.options.*.text' => 'nullable|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'questions.*.question.required' => 'The question field is required.',
            'questions.*.type.required' => 'The question type field is required.',
            'questions.*.type.in' => 'The selected question type is invalid. Allowed types are text, select, radio.',
        ];
    }
}
