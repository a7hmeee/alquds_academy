<?php

namespace App\Events;

use App\Models\StudentSubmission;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SubmissionCreated
{
    use Dispatchable, SerializesModels;

    public StudentSubmission $submission;

    public function __construct(StudentSubmission $submission)
    {
        $this->submission = $submission;
    }
}
