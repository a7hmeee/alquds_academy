<?php

namespace App\Features\StudentProgress\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StudentProgressResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'circle_id' => $this->circle_id,
            'student' => [
                'id' => $this->student?->id,
                'name' => $this->student?->user?->name ?? $this->student?->full_name,
                'email' => $this->student?->user?->email,
            ],
            'teacher' => $this->teacher ? [
                'id' => $this->teacher->id,
                'name' => $this->teacher->user?->name ?? $this->teacher->full_name,
                'email' => $this->teacher->user?->email,
            ] : null,
            'juz' => $this->juz,
            'surah' => $this->surah,
            'ayah' => $this->ayah,
            'notes' => $this->notes,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at?->toDateTimeString(),
        ];
    }
}
