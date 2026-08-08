<?php

namespace App\Listeners\Recordings;

use App\Events\SubmissionCreated;
use App\Features\StudentSubmissions\Notifications\NewSubmissionNotification;
use Illuminate\Support\Facades\Log;

class NotifyTeacherNewSubmission
{
    public function handle(SubmissionCreated $event): void
    {
        try {
            $circle = $event->submission->circle;
            $teacher = $circle?->circleTeachers->first()?->teacher;

            if ($teacher && $teacher->user) {
                $teacher->user->notify(
                    new NewSubmissionNotification($event->submission)
                );
            }
        } catch (\Throwable $e) {
            Log::warning('Failed to notify teacher about new submission: ' . $e->getMessage());
        }
    }
}
