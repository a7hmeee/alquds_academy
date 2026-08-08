<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTeacherRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'            => ['nullable', 'string', 'max:255'],
            'email'           => ['nullable', 'email', 'unique:users,email'],
            'password'        => ['nullable', 'min:6'],
            'user_id'         => ['nullable', 'exists:users,id'],
            'academic_degree' => ['required', 'in:hafiz,ijazah,bachelor,master,doctorate'],
            'photo'           => ['nullable', 'image', 'max:2048'],
            'bio'             => ['nullable', 'string'],
        ];
    }
}
