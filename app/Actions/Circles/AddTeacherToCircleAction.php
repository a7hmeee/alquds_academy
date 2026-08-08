<?php

namespace App\Actions\Circles;

use App\Models\Circle;
use App\Models\CircleTeacher;
use Illuminate\Support\Facades\DB;

class AddTeacherToCircleAction
{
    public function execute(Circle $circle, array $teacherIds, string $role, string $status): array
    {
        return DB::transaction(function () use ($circle, $teacherIds, $role, $status) {
            $added = 0;
            $skipped = 0;

            foreach ($teacherIds as $teacherId) {
                $exists = CircleTeacher::where('circle_id', $circle->id)
                    ->where('teacher_id', $teacherId)
                    ->exists();

                if ($exists) {
                    $skipped++;
                    continue;
                }

                CircleTeacher::create([
                    'circle_id'  => $circle->id,
                    'teacher_id' => $teacherId,
                    'role'       => $role,
                    'status'     => $status,
                ]);

                $added++;
            }

            return ['added' => $added, 'skipped' => $skipped];
        });
    }
}
