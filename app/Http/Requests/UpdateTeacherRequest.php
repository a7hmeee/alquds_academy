<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTeacherRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'academic_degree'    => ['required', 'in:hafiz,ijazah,bachelor,master,doctorate'],
            'years_of_experience'=> ['nullable', 'integer', 'min:0'],
            'specialization'     => ['nullable', 'string', 'max:255'],
            'riwayat'            => ['nullable', 'string', 'max:255'],
            'teaching_language'  => ['nullable', 'string', 'max:10'],
            'gender'             => ['nullable', 'in:male,female'],
            'bio'                => ['nullable', 'string'],
            'photo'              => ['nullable', 'image', 'max:2048'],
            'status'             => ['required', 'in:active,paused'],
        ];
    }
}
