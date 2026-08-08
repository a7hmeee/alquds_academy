<?php

namespace App\Features\StudentProgress\Actions;

use App\Models\StudentProgress;
use App\Features\StudentProgress\DTOs\StudentProgressData;
use App\Features\StudentProgress\Repositories\StudentProgressRepositoryInterface;

class UpdateStudentProgressAction
{
    public function __construct(protected StudentProgressRepositoryInterface $repo)
    {
    }

    public function execute(StudentProgress $progress, StudentProgressData $data)
    {
        return $this->repo->update($progress, $data);
    }
}
