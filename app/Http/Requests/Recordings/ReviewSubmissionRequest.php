<?php

namespace App\Http\Requests\Recordings;

use Illuminate\Foundation\Http\FormRequest;

class ReviewSubmissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();

        if (!$user) {
            return false;
        }

        // Any authenticated user can attempt to self-rate their own submission
        // Further authorization is handled by the controller/policy
        return true;
    }

    public function rules(): array
    {
        return [
            'self_rating' => 'nullable|integer|min:1|max:5',
            'notes' => 'nullable|string|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'self_rating.min' => 'التقييم يجب أن يكون 1 على الأقل',
            'self_rating.max' => 'التقييم يجب أن يكون 5 كحد أقصى',
            'notes.max' => 'الملاحظات لا تتجاوز 500 حرف',
        ];
    }
}
