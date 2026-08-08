<?php

namespace App\Http\Requests\Recordings;

use Illuminate\Foundation\Http\FormRequest;

class BulkImportRecordingRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();

        if (!$user) {
            return false;
        }

        // Only students can bulk import
        return $user->studentProfile !== null;
    }

    public function rules(): array
    {
        return [
            'file' => 'required|file|mimes:csv,xlsx,xls|max:5120',
        ];
    }

    public function messages(): array
    {
        return [
            'file.required' => 'يجب اختيار ملف للاستيراد',
            'file.mimes' => 'يجب أن يكون الملف بصيغة CSV أو Excel',
            'file.max' => 'حجم الملف لا يجب أن يتجاوز 5MB',
        ];
    }
}
