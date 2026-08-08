<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStudentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'full_name' => 'required|string|max:255',
            'phone'     => 'nullable|string|max:20',
            'teacher_id' => 'nullable|exists:teacher_profiles,id',
            'status'    => 'required|in:active,paused,archived',
            'photo'     => 'nullable|image|max:2048',
            'notes'     => 'nullable|string',
            'memorization_level' => 'nullable|string',
            'tajweed_level' => 'nullable|string',
            'surah_id' => 'nullable|integer|exists:surahs,id',
            'juz_id' => 'nullable|integer|exists:juz,id',
            'ayah' => 'nullable|integer',
        ];
    }
}
