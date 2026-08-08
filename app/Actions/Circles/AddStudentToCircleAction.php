<?php

namespace App\Actions\Circles;

use App\Models\Circle;
use App\Models\CircleStudent;
use Illuminate\Support\Facades\DB;

class AddStudentToCircleAction
{
    public function execute(Circle $circle, array $validated): CircleStudent
    {
        $exists = CircleStudent::where('circle_id', $circle->id)
            ->where('student_id', $validated['student_id'])
            ->exists();

        if ($exists) {
            throw new \RuntimeException('هذا الطالب مرتبط بالحَلقة مسبقًا');
        }

        return DB::transaction(function () use ($circle, $validated) {
            $circle->circleStudents()->lockForUpdate()->count();

            if (!$circle->hasCapacity()) {
                throw new \RuntimeException('الحلقة مكتملة العدد');
            }

            return CircleStudent::create([
                'circle_id'  => $circle->id,
                'student_id' => $validated['student_id'],
                'status'     => $validated['status'],
                'joined_at'  => $validated['joined_at'] ?? now(),
            ]);
        });
    }
}
