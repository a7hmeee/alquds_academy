<?php

namespace App\Policies;

use App\Models\User;
use App\Models\StudentSubmission;

class StudentSubmissionPolicy
{
    public function view(User $user, StudentSubmission $submission): bool
    {
        if ($user->hasRole('super admin')) return true;
        if ($user->hasRole('admin')) return true;
        if ($user->studentProfile && $user->studentProfile->id === $submission->student_id) return true;
        if ($user->teacherProfile) {
            return $submission->circle
                ->circleTeachers()
                ->where('teacher_id', $user->teacherProfile->id)
                ->exists();
        }
        return false;
    }

    public function create(User $user): bool
    {
        return $user->studentProfile !== null || $user->hasAnyRole(['super admin', 'admin', 'teacher']);
    }

    public function review(User $user, StudentSubmission $submission): bool
    {
        if ($user->hasRole('super admin')) return true;
        if ($user->hasRole('admin')) return true;
        if ($user->teacherProfile) {
            return $submission->circle
                ->circleTeachers()
                ->where('teacher_id', $user->teacherProfile->id)
                ->exists();
        }
        return false;
    }

    public function delete(User $user, StudentSubmission $submission): bool
    {
        if ($user->hasRole('super admin')) return true;
        if ($user->studentProfile && $user->studentProfile->id === $submission->student_id) return true;
        return false;
    }
}
