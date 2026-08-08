<?php

namespace App\Actions\Circles;

use App\Models\Circle;
use App\Models\StudentProfile;
use App\Models\CircleStudent;
use Illuminate\Support\Facades\DB;

class JoinCircleAction
{
    public function execute(StudentProfile $student, Circle $circle): CircleStudent
    {
        if ($circle->status !== 'active') {
            throw new \RuntimeException('هذه الحلقة غير نشطة حالياً');
        }

        $exists = $circle->circleStudents()->where('student_id', $student->id)->exists();
        if ($exists) {
            throw new \RuntimeException('أنت مسجل بالفعل في هذه الحلقة');
        }

        return DB::transaction(function () use ($circle, $student) {
            $circle->fresh();
            $circle->circleStudents()->lockForUpdate()->count();

            if (!$circle->hasCapacity()) {
                throw new \RuntimeException('الحلقة مكتملة العدد');
            }

            return $circle->circleStudents()->create([
                'circle_id'  => $circle->id,
                'student_id' => $student->id,
                'status'     => 'active',
                'joined_at'  => now(),
            ]);
        });
    }
}
