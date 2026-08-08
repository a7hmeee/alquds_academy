<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMemorizationAssignmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('memorization_assignment'));
    }

    public function rules(): array
    {
        return [
            'assignment_type' => ['sometimes', 'required', 'in:new_memorization,close_revision,far_revision,consolidation,test'],
            'surah_id' => ['sometimes', 'required', 'exists:surahs,id'],
            'juz_id' => ['sometimes', 'required', 'exists:juz,id'],
            'ayah_from' => ['sometimes', 'required', 'integer', 'min:1'],
            'ayah_to' => ['sometimes', 'required', 'integer', 'gte:ayah_from'],
            'priority' => ['nullable', 'integer', 'min:0', 'max:5'],
            'instructions' => ['nullable', 'string', 'max:2000'],
            'due_at' => ['nullable', 'date'],
            'status' => ['sometimes', 'required', 'in:draft,assigned,in_progress,submitted,reviewed,completed,needs_revision,cancelled'],
        ];
    }

    public function messages(): array
    {
        return [
            'assignment_type.in' => 'نوع المهمة غير صالح',
            'ayah_to.gte' => 'إلى آية يجب أن تكون أكبر من أو تساوي من آية',
        ];
    }
}
