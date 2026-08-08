<?php

namespace App\Policies;

use App\Models\User;
use App\Models\MemorizationAssignment;

class MemorizationAssignmentPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('memorization-assignments.view');
    }

    public function view(User $user, MemorizationAssignment $assignment): bool
    {
        if ($user->hasPermissionTo('memorization-assignments.view')) {
            if ($user->isSuperAdmin() || $user->isAdmin()) {
                return true;
            }
            if ($user->isTeacher() && $user->teacherProfile?->id === $assignment->teacher_id) {
                return true;
            }
            if ($user->isStudent() && $user->studentProfile?->id === $assignment->student_id) {
                return true;
            }
        }
        return false;
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('memorization-assignments.create');
    }

    public function update(User $user, MemorizationAssignment $assignment): bool
    {
        if (! $user->hasPermissionTo('memorization-assignments.update')) {
            return false;
        }
        if ($user->isSuperAdmin() || $user->isAdmin()) {
            return true;
        }
        if ($user->isTeacher() && $user->teacherProfile?->id === $assignment->teacher_id) {
            return true;
        }
        return false;
    }

    public function delete(User $user, MemorizationAssignment $assignment): bool
    {
        if (! $user->hasPermissionTo('memorization-assignments.delete')) {
            return false;
        }
        if ($user->isSuperAdmin() || $user->isAdmin()) {
            return true;
        }
        if ($user->isTeacher() && $user->teacherProfile?->id === $assignment->teacher_id) {
            return in_array($assignment->status, ['draft', 'assigned']);
        }
        return false;
    }

    public function review(User $user, MemorizationAssignment $assignment): bool
    {
        return $user->hasPermissionTo('memorization-assignments.review')
            && ($user->isSuperAdmin() || $user->isAdmin()
                || ($user->isTeacher() && $user->teacherProfile?->id === $assignment->teacher_id));
    }
}
