<?php

namespace App\Actions\Memorization;

use App\Models\MemorizationAssignment;
use App\Services\JuzProgressService;

class CompleteMemorizationAssignmentAction
{
    public function __construct(
        private JuzProgressService $juzProgressService,
    ) {}

    public function execute(MemorizationAssignment $assignment, int $completionPercent = 100): MemorizationAssignment
    {
        if (!$assignment->canTransitionTo('completed')) {
            throw new \InvalidArgumentException('لا يمكن إكمال مهمة بحالة: ' . $assignment->status);
        }

        $assignment->update([
            'status' => 'completed',
            'completion_percent' => min(100, max(0, $completionPercent)),
            'completed_at' => now(),
            'reviewed_at' => now(),
        ]);

        $this->juzProgressService->clearStudentCache($assignment->student_id, $assignment->juz_id);

        return $assignment;
    }
}
