<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStudentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id'   => 'nullable|exists:users,id',
            'email'     => 'nullable|email|unique:users,email',
            'password'  => 'nullable|string|min:6',
            'full_name' => 'required|string|max:255',
            'phone'     => 'nullable|string|max:20',
            'birth_date' => 'nullable|date',
            'gender' => 'nullable|in:male,female',
            'guardian_name' => 'nullable|string|max:255',
            'guardian_phone' => 'nullable|string|max:20',
            'status'    => 'required|in:active,paused,archived',
            'photo'     => 'nullable|image|max:2048',
            'notes'     => 'nullable|string',
            'memorization_level' => 'nullable|string',
            'tajweed_level' => 'nullable|string',
            'surah_id' => 'nullable|integer|exists:surahs,id',
            'juz_id' => 'nullable|integer|exists:juz,id',
            'ayah' => 'nullable|integer',
            'is_smart_mode' => 'nullable|boolean',
            'needs_assistance' => 'nullable|boolean',
        ];
    }
}
