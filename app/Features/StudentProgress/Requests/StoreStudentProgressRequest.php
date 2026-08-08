<?php

namespace App\Features\StudentProgress\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStudentProgressRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() != null; // further policy checks can be added
    }

    public function rules(): array
    {
        return [
            'circle_id'  => ['required','exists:circles,id'],
            'student_id' => ['required','exists:student_profiles,id'],
            'teacher_id' => ['nullable','exists:teacher_profiles,id'],
            'juz'        => ['nullable','string','max:50'],
            'surah'      => ['nullable','string','max:255'],
            'ayah'       => ['nullable','integer','min:1'],
            'notes'      => ['nullable','string'],
        ];
    }
}
