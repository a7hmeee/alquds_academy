<?php

namespace App\Events;

use App\Models\MemorizationAssignment;
use Illuminate\Foundation\Events\Dispatchable;

class MemorizationAssignmentCreated
{
    use Dispatchable;

    public function __construct(
        public MemorizationAssignment $assignment,
    ) {}
}
