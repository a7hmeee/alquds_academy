<?php

namespace App\Features\StudentSubmissions\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewSubmissionNotification extends Notification
{
    use Queueable;

    public $submission;
    public $isReview;

    public function __construct($submission, $isReview = false)
    {
        $this->submission = $submission;
        $this->isReview = $isReview;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        if ($this->isReview) {
            return [
                'message' => 'تم تقييم صوتيتك من قبل المعلم',
                'submission_id' => $this->submission->id,
                'circle_id' => $this->submission->circle_id,
            ];
        }

        return [
            'message' => 'تم رفع صوتية جديدة من الطالب ' . ($this->submission->student?->user?->name ?? ''),
            'submission_id' => $this->submission->id,
            'circle_id' => $this->submission->circle_id,
        ];
    }
}
