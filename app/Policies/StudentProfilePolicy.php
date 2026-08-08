<?php

namespace App\Policies;

use App\Models\User;
use App\Models\StudentProfile;

class StudentProfilePolicy
{
    public function view(User $user, StudentProfile $student): bool
    {
        if ($user->hasRole('super admin')) return true;
        if ($user->hasRole('admin')) return true;
        if ($user->teacherProfile) {
            return $user->teacherProfile->id === $student->teacher_id
                || $student->circles()->whereIn('circle_id', $user->teacherProfile->circles()->pluck('circles.id'))->exists();
        }
        return $user->id === $student->user_id;
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['super admin', 'admin', 'teacher']);
    }

    public function update(User $user, StudentProfile $student): bool
    {
        return $this->view($user, $student);
    }
}
