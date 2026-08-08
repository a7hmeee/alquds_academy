<?php

namespace App\Features\StudentProgress\Notifications;

use App\Models\StudentProgress;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;

class StudentProgressNotification extends Notification
{
    use Queueable;

    public function __construct(protected StudentProgress $progress)
    {
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'message' => 'تم إضافة سجل تقدّم جديد للطالب ' . ($this->progress->student?->user?->name ?? $this->progress->student?->full_name),
            'progress_id' => $this->progress->id,
            'circle_id' => $this->progress->circle_id,
        ];
    }
}
