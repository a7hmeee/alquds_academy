<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCircleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'            => 'required|string|max:255',
            'organization_id' => 'nullable|exists:organizations,id',
            'type'            => 'required|in:onsite,online,hybrid',
            'level'           => 'nullable|string|max:255',
            'capacity'        => 'nullable|integer|min:1',
            'juz_id'          => 'nullable|exists:juz,id',
            'status'          => 'required|in:active,paused,archived',
            'description'     => 'nullable|string',
        ];
    }
}
