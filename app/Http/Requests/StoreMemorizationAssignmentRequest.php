<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMemorizationAssignmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', MemorizationAssignment::class);
    }

    public function rules(): array
    {
        return [
            'student_id' => ['required', 'exists:student_profiles,id'],
            'circle_id' => ['required', 'exists:circles,id'],
            'assignment_type' => ['required', 'in:new_memorization,close_revision,far_revision,consolidation,test'],
            'surah_id' => ['required', 'exists:surahs,id'],
            'juz_id' => ['required', 'exists:juz,id'],
            'ayah_from' => ['required', 'integer', 'min:1'],
            'ayah_to' => ['required', 'integer', 'gte:ayah_from'],
            'priority' => ['nullable', 'integer', 'min:0', 'max:5'],
            'status' => ['nullable', 'in:draft,assigned'],
            'instructions' => ['nullable', 'string', 'max:2000'],
            'due_at' => ['nullable', 'date', 'after_or_equal:today'],
        ];
    }

    public function messages(): array
    {
        return [
            'student_id.required' => 'يجب اختيار الطالب',
            'student_id.exists' => 'الطالب غير موجود',
            'circle_id.required' => 'يجب اختيار الحلقة',
            'assignment_type.required' => 'نوع المهمة مطلوب',
            'assignment_type.in' => 'نوع المهمة غير صالح',
            'surah_id.required' => 'يجب اختيار السورة',
            'juz_id.required' => 'يجب اختيار الجزء',
            'ayah_from.required' => 'من آية مطلوب',
            'ayah_to.required' => 'إلى آية مطلوب',
            'ayah_to.gte' => 'إلى آية يجب أن تكون أكبر من أو تساوي من آية',
            'due_at.after_or_equal' => 'تاريخ الاستحقاق يجب أن يكون اليوم أو later',
        ];
    }
}
