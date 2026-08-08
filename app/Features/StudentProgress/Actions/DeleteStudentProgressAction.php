<?php

namespace App\Features\StudentProgress\Actions;

use App\Models\StudentProgress;
use App\Features\StudentProgress\Repositories\StudentProgressRepositoryInterface;

class DeleteStudentProgressAction
{
    public function __construct(protected StudentProgressRepositoryInterface $repo)
    {
    }

    public function execute(StudentProgress $progress): bool
    {
        return $this->repo->delete($progress);
    }
}
