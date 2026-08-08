<?php

namespace App\Queries\Circles;

use App\Models\Circle;
use App\Models\TeacherProfile;
use App\Models\StudentProfile;
use App\Services\JuzProgressService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;

class CircleShowQuery
{
    public function get(Circle $circle): array
    {
        $circle->load([
            'organization',
            'juz',
            'circleTeachers.teacher.user',
            'circleStudents.student.user',
        ]);

        $circle->setRelation('circleTeachers', $circle->circleTeachers
            ->sortBy(fn($ct) => strtolower($ct->teacher?->user?->email ?? $ct->teacher?->full_name ?? ''))
            ->values()
        );

        $circle->setRelation('circleStudents', $circle->circleStudents
            ->sortBy(fn($cs) => strtolower($cs->student?->user?->email ?? $cs->student?->full_name ?? ''))
            ->values()
        );

        $availableTeachers = TeacherProfile::select('teacher_profiles.*')
            ->join('users', 'users.id', '=', 'teacher_profiles.user_id')
            ->with('user')
            ->orderBy('users.email')
            ->get();

        $enrolledIds = $circle->circleStudents()->pluck('student_id');
        $availableStudents = StudentProfile::with('user')
            ->whereNotIn('id', $enrolledIds)
            ->orderBy('id', 'desc')
            ->get();

        $studentHasTeacherColumn = Schema::hasColumn('student_profiles', 'teacher_id');

        $studentsProgress = collect();
        if ($circle->juz_id) {
            foreach ($circle->circleStudents as $cs) {
                if ($cs->student) {
                    $studentsProgress[$cs->student->id] = JuzProgressService::calculate(
                        $cs->student->id, $circle->juz_id, $circle->id
                    );
                }
            }
        }

        return compact('circle', 'availableTeachers', 'availableStudents', 'studentHasTeacherColumn', 'studentsProgress');
    }
}
