<?php

namespace App\Features\StudentProgress\Actions;

use App\Features\StudentProgress\DTOs\StudentProgressData;
use App\Features\StudentProgress\Repositories\StudentProgressRepositoryInterface;
use App\Features\StudentProgress\Events\StudentProgressCreated;

class CreateStudentProgressAction
{
    public function __construct(protected StudentProgressRepositoryInterface $repo)
    {
    }

    public function execute(StudentProgressData $data)
    {
        $progress = $this->repo->create($data);

        event(new StudentProgressCreated($progress));

        return $progress;
    }
}
