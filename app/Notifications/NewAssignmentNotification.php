<?php

namespace App\Notifications;

use App\Models\MemorizationAssignment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewAssignmentNotification extends Notification
{
    use Queueable;

    public function __construct(
        public MemorizationAssignment $assignment,
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $typeLabels = [
            'new_memorization' => 'حفظ جديد',
            'close_revision' => 'مراجعة قريبة',
            'far_revision' => 'مراجعة بعيدة',
            'consolidation' => 'تثبيت',
            'test' => 'اختبار',
        ];

        return [
            'message' => 'تم إسناد مهمة جديدة لك: ' . ($typeLabels[$this->assignment->assignment_type] ?? $this->assignment->assignment_type),
            'assignment_id' => $this->assignment->id,
            'assignment_type' => $this->assignment->assignment_type,
            'surah' => $this->assignment->surah?->name_ar,
            'ayah_from' => $this->assignment->ayah_from,
            'ayah_to' => $this->assignment->ayah_to,
            'due_at' => $this->assignment->due_at?->toDateString(),
            'circle_id' => $this->assignment->circle_id,
        ];
    }
}
