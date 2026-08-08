<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Circle;

class CirclePolicy
{
    public function view(User $user, Circle $circle): bool
    {
        if ($user->hasAnyRole(['super admin', 'admin'])) return true;
        if ($user->teacherProfile) {
            return $circle->circleTeachers()->where('teacher_id', $user->teacherProfile->id)->exists();
        }
        if ($user->studentProfile) {
            return $circle->circleStudents()->where('student_id', $user->studentProfile->id)->exists();
        }
        return false;
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['super admin', 'admin']);
    }

    public function update(User $user, Circle $circle): bool
    {
        return $user->hasAnyRole(['super admin', 'admin']);
    }
}
