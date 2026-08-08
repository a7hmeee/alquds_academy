<?php

namespace App\Features\StudentProgress\Repositories;

use App\Models\StudentProgress;
use App\Features\StudentProgress\DTOs\StudentProgressData;

class EloquentStudentProgressRepository implements StudentProgressRepositoryInterface
{
    public function create(StudentProgressData $data): StudentProgress
    {
        return StudentProgress::create($data->toArray());
    }

    public function update(StudentProgress $progress, StudentProgressData $data): StudentProgress
    {
        $progress->update($data->toArray());
        return $progress->refresh();
    }

    public function delete(StudentProgress $progress): bool
    {
        return $progress->delete();
    }

    public function find(int $id): ?StudentProgress
    {
        return StudentProgress::with(['student.user','teacher.user','creator'])->find($id);
    }

    public function listByCircle(int $circleId)
    {
        return StudentProgress::with(['student.user','teacher.user'])
            ->where('circle_id', $circleId)
            ->orderByDesc('created_at')
            ->get();
    }
}
