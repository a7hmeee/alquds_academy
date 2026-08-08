<?php

namespace App\Features\StudentSubmissions\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStudentSubmissionRequest extends FormRequest
{
    public function authorize()
    {
        $user = $this->user();
        // allow when user is a student, teacher, or super admin
        return $user && (
            $user->studentProfile ||
            $user->teacherProfile ||
            $user->hasRole('super admin')
        );
    }

    public function rules()
    {
        return [
            'student_id' => 'nullable|exists:student_profiles,id',
            'audio_file' => 'required|file|mimes:mp3,wav,m4a|max:10240',
            'image' => 'nullable|image|max:2048',
            'surah' => 'nullable|string|max:191',
            'ayah' => 'nullable|integer|min:1',
            'juz' => 'nullable|string|max:100',
            'surah_id' => 'nullable|exists:surahs,id',
            'juz_id' => 'nullable|exists:juz,id',
            'ayah_from' => 'nullable|integer|min:1',
            'ayah_to' => 'nullable|integer|min:1',
            'notes' => 'nullable|string',
        ];
    }
}
