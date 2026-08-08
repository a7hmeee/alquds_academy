<?php

namespace App\Features\StudentProgress\Events;

use App\Models\StudentProgress;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class StudentProgressCreated
{
    use Dispatchable, SerializesModels;

    public StudentProgress $progress;

    public function __construct(StudentProgress $progress)
    {
        $this->progress = $progress;
    }
}
