<?php

namespace App\Listeners\Memorization;

use App\Events\MemorizationAssignmentCreated;
use App\Notifications\NewAssignmentNotification;

class NotifyStudentNewAssignment
{
    public function handle(MemorizationAssignmentCreated $event): void
    {
        $assignment = $event->assignment;
        $student = $assignment->student;

        if ($student && $student->user) {
            try {
                $student->user->notify(new NewAssignmentNotification($assignment));
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::warning('Failed to notify student about assignment: ' . $e->getMessage());
            }
        }
    }
}
