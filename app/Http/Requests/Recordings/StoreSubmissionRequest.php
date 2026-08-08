<?php

namespace App\Http\Requests\Recordings;

use App\Models\CircleStudent;
use Illuminate\Foundation\Http\FormRequest;

class StoreSubmissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();

        if (!$user) {
            return false;
        }

        // Student can create their own submissions
        if ($user->studentProfile) {
            return true;
        }

        // Teacher, admin, super admin can create submissions
        return $user->hasAnyRole(['super admin', 'admin', 'teacher']);
    }

    public function rules(): array
    {
        return [
            'circle_id' => [
                'required',
                'exists:circles,id',
                function ($attribute, $value, $fail) {
                    $user = $this->user();
                    $student = $user->studentProfile;

                    if (!$student) {
                        $fail('يجب أن يكون لديك ملف طالب لرفع تسجيل');
                        return;
                    }

                    $belongs = CircleStudent::where('circle_id', $value)
                        ->where('student_id', $student->id)
                        ->where('status', 'active')
                        ->exists();

                    if (!$belongs) {
                        $fail('أنت غير مسجل في هذه الحلقة، أو عضويتك غير نشطة');
                    }
                },
            ],
            'audio' => 'required|file|mimes:mp3,wav,m4a,ogg|max:51200',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'surah_id' => 'required|exists:surahs,id',
            'juz_id' => 'required|exists:juz,id',
            'ayah_from' => 'required|integer|min:1',
            'ayah_to' => 'nullable|integer|min:1',
            'notes' => 'nullable|string|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'circle_id.required' => 'الحلقة مطلوبة',
            'audio.required' => 'الملف الصوتي مطلوب',
            'audio.mimes' => 'الملف الصوتي يجب أن يكون mp3, wav, m4a, أو ogg',
            'audio.max' => 'حجم الملف الصوتي لا يجب أن يتجاوز 50MB',
            'surah_id.required' => 'السورة مطلوبة',
            'juz_id.required' => 'الجزء مطلوب',
            'ayah_from.required' => 'رقم الآية مطلوب',
            'ayah_from.min' => 'رقم الآية يجب أن يكون 1 على الأقل',
        ];
    }
}
