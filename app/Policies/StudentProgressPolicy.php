<?php

namespace App\Policies;

use App\Models\User;
use App\Models\StudentProgress;

class StudentProgressPolicy
{
    public function view(User $user, StudentProgress $progress): bool
    {
        // allow if admin or teacher or creator or teacher of the student
        return $user->hasRole('super admin')
            || $user->hasRole('admin')
            || $user->hasRole('teacher')
            || $user->id === $progress->created_by;
    }

    public function create(User $user): bool
    {
        return $user->hasRole('teacher') || $user->hasRole('admin') || $user->hasRole('super admin');
    }

    public function update(User $user, StudentProgress $progress): bool
    {
        return $user->hasRole('super admin')
            || $user->hasRole('admin')
            || $user->hasRole('teacher')
            || $user->id === $progress->created_by;
    }

    public function delete(User $user, StudentProgress $progress): bool
    {
        return $user->hasRole('super admin') || $user->id === $progress->created_by;
    }
}
