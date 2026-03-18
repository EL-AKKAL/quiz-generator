<?php

namespace App\Http\Requests;

use App\OptionScoreEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

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

        $requestStatus = null;
        if ($this->has('status')) {
            $requestStatus = filter_var($this->status, FILTER_VALIDATE_BOOLEAN);
        }

        $expireDate = $this->expire_date ? \Carbon\Carbon::parse($this->expire_date) : null;
        $today = \Carbon\Carbon::today();

        $finalStatus = ($expireDate && $expireDate->lt($today)) ? false : $requestStatus;

        $this->merge([
            'status' => $finalStatus,
        ]);
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
            'questions.*.data.options.*.score' => [
                'required_with:questions.*.data.options',
                new Enum(OptionScoreEnum::class),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'questions.*.question.required' => 'The question field is required.',
            'questions.*.type.required' => 'The question type field is required.',
            'questions.*.type.in' => 'The selected question type is invalid. Allowed types are text, select, radio,checkbox.',
            'questions.*.data.options.*.score' => 'questions with options must have score',
        ];
    }
}
