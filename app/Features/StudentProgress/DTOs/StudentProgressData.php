<?php

namespace App\Features\StudentProgress\DTOs;

class StudentProgressData
{
    public int $circle_id;
    public int $student_id;
    public ?int $teacher_id;
    public ?string $juz;
    public ?string $surah;
    public ?int $ayah;
    public ?string $notes;
    public ?int $created_by;

    public function __construct(array $data)
    {
        $this->circle_id  = (int) ($data['circle_id'] ?? 0);
        $this->student_id = (int) ($data['student_id'] ?? 0);
        $this->teacher_id = isset($data['teacher_id']) ? (int) $data['teacher_id'] : null;
        $this->juz        = $data['juz'] ?? null;
        $this->surah      = $data['surah'] ?? null;
        $this->ayah       = isset($data['ayah']) ? (int) $data['ayah'] : null;
        $this->notes      = $data['notes'] ?? null;
        $this->created_by = isset($data['created_by']) ? (int) $data['created_by'] : null;
    }

    public static function fromRequest($request): self
    {
        return new self([
            'circle_id'  => $request->input('circle_id'),
            'student_id' => $request->input('student_id'),
            'teacher_id' => $request->input('teacher_id'),
            'juz'        => $request->input('juz'),
            'surah'      => $request->input('surah'),
            'ayah'       => $request->input('ayah'),
            'notes'      => $request->input('notes'),
            'created_by' => $request->user()?->id ?? null,
        ]);
    }

    public function toArray(): array
    {
        return [
            'circle_id'  => $this->circle_id,
            'student_id' => $this->student_id,
            'teacher_id' => $this->teacher_id,
            'juz'        => $this->juz,
            'surah'      => $this->surah,
            'ayah'       => $this->ayah,
            'notes'      => $this->notes,
            'created_by' => $this->created_by,
        ];
    }
}
