<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMemorizationSessionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('memorization_session'));
    }

    public function rules(): array
    {
        return [
            'duratiominutes' => ['nullable', 'integer', 'min:1', 'max:480'],
            'memorization_score' => ['nullable', 'integer', 'min:0', 'max:100'],
            'tajweed_score' => ['nullable', 'integer', 'min:0', 'max:100'],
            'fluency_score' => ['nullable', 'integer', 'min:0', 'max:100'],
            'total_score' => ['nullable', 'integer', 'min:0', 'max:100'],
            'status' => ['nullable', 'in:completed,failed,rescheduled'],
            'teacher_notes' => ['nullable', 'string', 'max:5000'],
            'student_notes' => ['nullable', 'string', 'max:5000'],
        ];
    }
}
