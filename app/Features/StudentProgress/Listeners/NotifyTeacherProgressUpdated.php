<?php

namespace App\Features\StudentProgress\Listeners;

use App\Features\StudentProgress\Events\StudentProgressCreated;
use App\Features\StudentProgress\Notifications\StudentProgressNotification;

class NotifyTeacherProgressUpdated
{
    public function handle(StudentProgressCreated $event)
    {
        $progress = $event->progress;

        // إذا معلم موجود ومرتبط بمستخدم
        $teacher = $progress->teacher;
        if ($teacher && $teacher->user) {
            $teacher->user->notify(new StudentProgressNotification($progress));
        }
    }
}
