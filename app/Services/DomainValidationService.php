<?php

namespace App\Services;

use App\Models\Circle;
use App\Models\StudentProfile;
use App\Models\TeacherProfile;

class DomainValidationService
{
    public static function studentBelongsToCircle(int $studentId, int $circleId, bool $requireActive = true): bool
    {
        $query = \App\Models\CircleStudent::where('circle_id', $circleId)
            ->where('student_id', $studentId);

        if ($requireActive) {
            $query->where('status', 'active');
        }

        return $query->exists();
    }

    public static function teacherBelongsToCircle(int $teacherId, int $circleId, bool $requireActive = true): bool
    {
        $query = \App\Models\CircleTeacher::where('circle_id', $circleId)
            ->where('teacher_id', $teacherId);

        if ($requireActive) {
            $query->where('status', 'active');
        }

        return $query->exists();
    }

    public static function studentHasTeacherInCircle(int $studentId, int $teacherId, int $circleId): bool
    {
        // First check student belongs to circle
        if (!self::studentBelongsToCircle($studentId, $circleId)) {
            return false;
        }

        // Then check teacher belongs to same circle
        return self::teacherBelongsToCircle($teacherId, $circleId);
    }

    public static function submissionIsValid(int $studentId, int $circleId, ?int $teacherId = null): bool
    {
        if (!self::studentBelongsToCircle($studentId, $circleId)) {
            return false;
        }

        if ($teacherId && !self::teacherBelongsToCircle($teacherId, $circleId)) {
            return false;
        }

        return true;
    }

    public static function getStudentPrimaryTeacher(int $studentId): ?TeacherProfile
    {
        $circleStudent = \App\Models\CircleStudent::where('student_id', $studentId)
            ->where('status', 'active')
            ->with(['circle.circleTeachers' => function ($q) {
                $q->where('role', 'primary');
            }, 'circle.circleTeachers.teacher'])
            ->first();

        return $circleStudent?->circle?->circleTeachers?->first()?->teacher;
    }
}
