<?php

namespace App\Actions\Memorization;

use App\Models\MemorizationAssignment;
use App\Events\MemorizationAssignmentCreated;
use App\Services\DomainValidationService;

class CreateMemorizationAssignmentAction
{
    public function __construct(
        private DomainValidationService $domainValidation,
    ) {}

    public function execute(array $data, int $createdBy): MemorizationAssignment
    {
        $studentId = $data['student_id'];
        $circleId = $data['circle_id'];
        $teacherId = $data['teacher_id'] ?? null;

        if (!$this->domainValidation->studentBelongsToCircle($studentId, $circleId)) {
            throw new \InvalidArgumentException('الطالب ليس عضوًا في هذه الحلقة');
        }

        if ($teacherId && !$this->domainValidation->teacherBelongsToCircle($teacherId, $circleId)) {
            throw new \InvalidArgumentException('المعلم ليس مرتبطًا بهذه الحلقة');
        }

        $assignment = MemorizationAssignment::create([
            'student_id' => $studentId,
            'teacher_id' => $teacherId ?? auth()->user()->teacherProfile?->id,
            'circle_id' => $circleId,
            'assignment_type' => $data['assignment_type'],
            'surah_id' => $data['surah_id'],
            'juz_id' => $data['juz_id'],
            'ayah_from' => $data['ayah_from'],
            'ayah_to' => $data['ayah_to'],
            'priority' => $data['priority'] ?? 0,
            'status' => $data['status'] ?? 'assigned',
            'instructions' => $data['instructions'] ?? null,
            'assigned_at' => $data['status'] === 'assigned' ? now() : null,
            'due_at' => $data['due_at'] ?? null,
            'completion_percent' => 0,
            'created_by' => $createdBy,
        ]);

        event(new MemorizationAssignmentCreated($assignment));

        return $assignment;
    }
}
