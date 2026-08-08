<?php

namespace App\Features\StudentProgress\Services;

use App\Features\StudentProgress\DTOs\StudentProgressData;
use App\Models\StudentProgress;
use App\Features\StudentProgress\Actions\CreateStudentProgressAction;
use App\Features\StudentProgress\Actions\UpdateStudentProgressAction;
use App\Features\StudentProgress\Actions\DeleteStudentProgressAction;

class StudentProgressService
{
    public function __construct(
        protected CreateStudentProgressAction $createAction,
        protected UpdateStudentProgressAction $updateAction,
        protected DeleteStudentProgressAction $deleteAction
    ) {
    }

    public function create(StudentProgressData $data): StudentProgress
    {
        return $this->createAction->execute($data);
    }

    public function update(StudentProgress $progress, StudentProgressData $data): StudentProgress
    {
        return $this->updateAction->execute($progress, $data);
    }

    public function delete(StudentProgress $progress): bool
    {
        return $this->deleteAction->execute($progress);
    }
}
