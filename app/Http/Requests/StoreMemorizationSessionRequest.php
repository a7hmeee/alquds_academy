<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMemorizationSessionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', \App\Models\MemorizationSession::class);
    }

    public function rules(): array
    {
        return [
            'memorization_assignment_id' => ['nullable', 'exists:memorization_assignments,id'],
            'student_id' => ['required', 'exists:student_profiles,id'],
            'circle_id' => ['required', 'exists:circles,id'],
            'session_type' => ['required', 'in:memorization,review,test,tajweed'],
            'surah_id' => ['required', 'exists:surahs,id'],
            'juz_id' => ['required', 'exists:juz,id'],
            'ayah_from' => ['required', 'integer', 'min:1'],
            'ayah_to' => ['required', 'integer', 'gte:ayah_from'],
            'session_date' => ['required', 'date'],
            'duration_minutes' => ['nullable', 'integer', 'min:1', 'max:480'],
            'memorization_score' => ['nullable', 'integer', 'min:0', 'max:100'],
            'tajweed_score' => ['nullable', 'integer', 'min:0', 'max:100'],
            'fluency_score' => ['nullable', 'integer', 'min:0', 'max:100'],
            'total_score' => ['nullable', 'integer', 'min:0', 'max:100'],
            'status' => ['nullable', 'in:completed,failed,rescheduled'],
            'teacher_notes' => ['nullable', 'string', 'max:5000'],
            'student_notes' => ['nullable', 'string', 'max:5000'],
        ];
    }

    public function messages(): array
    {
        return [
            'student_id.required' => 'يجب اختيار الطالب',
            'circle_id.required' => 'يجب اختيار الحلقة',
            'session_type.required' => 'نوع الجلسة مطلوب',
            'surah_id.required' => 'يجب اختيار السورة',
            'juz_id.required' => 'يجب اختيار الجزء',
            'ayah_from.required' => 'من آية مطلوب',
            'ayah_to.required' => 'إلى آية مطلوب',
            'ayah_to.gte' => 'إلى آية يجب أن تكون أكبر من أو تساوي من آية',
            'session_date.required' => 'تاريخ الجلسة مطلوب',
        ];
    }
}
