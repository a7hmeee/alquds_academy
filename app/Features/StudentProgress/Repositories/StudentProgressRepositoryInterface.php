<?php

namespace App\Features\StudentProgress\Repositories;

use App\Models\StudentProgress;
use App\Features\StudentProgress\DTOs\StudentProgressData;

interface StudentProgressRepositoryInterface
{
    public function create(StudentProgressData $data): StudentProgress;
    public function update(StudentProgress $progress, StudentProgressData $data): StudentProgress;
    public function delete(StudentProgress $progress): bool;
    public function find(int $id): ?StudentProgress;
    public function listByCircle(int $circleId);
}
