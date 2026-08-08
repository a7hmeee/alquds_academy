<?php

namespace App\Actions\Memorization;

use App\Models\MemorizationAssignment;

class UpdateMemorizationAssignmentAction
{
    public function execute(MemorizationAssignment $assignment, array $data): MemorizationAssignment
    {
        $allowedStatusTransition = [
            'draft' => ['assigned', 'cancelled'],
            'assigned' => ['in_progress', 'cancelled'],
            'in_progress' => ['submitted', 'cancelled'],
        ];

        if (isset($data['status'])) {
            $current = $assignment->status;
            if (!in_array($data['status'], $allowedStatusTransition[$current] ?? [])) {
                throw new \InvalidArgumentException('لا يمكن تغيير الحالة من ' . $current . ' إلى ' . $data['status']);
            }

            if ($data['status'] === 'assigned' && !$assignment->assigned_at) {
                $data['assigned_at'] = now();
            }
            if ($data['status'] === 'in_progress' && !$assignment->started_at) {
                $data['started_at'] = now();
            }
            if ($data['status'] === 'submitted' && !$assignment->submitted_at) {
                $data['submitted_at'] = now();
            }
        }

        $assignment->update($data);
        return $assignment;
    }
}
