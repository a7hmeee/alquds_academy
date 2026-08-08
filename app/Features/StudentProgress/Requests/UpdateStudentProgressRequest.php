<?php

namespace App\Features\StudentProgress\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStudentProgressRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() != null;
    }

    public function rules(): array
    {
        return [
            'teacher_id' => ['nullable','exists:teacher_profiles,id'],
            'juz'        => ['nullable','string','max:50'],
            'surah'      => ['nullable','string','max:255'],
            'ayah'       => ['nullable','integer','min:1'],
            'notes'      => ['nullable','string'],
        ];
    }
}
