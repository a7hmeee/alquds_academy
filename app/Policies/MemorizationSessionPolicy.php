<?php

namespace App\Policies;

use App\Models\User;
use App\Models\MemorizationSession;

class MemorizationSessionPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('memorization-sessions.view');
    }

    public function view(User $user, MemorizationSession $session): bool
    {
        if (! $user->hasPermissionTo('memorization-sessions.view')) return false;
        if ($user->isSuperAdmin() || $user->isAdmin()) return true;
        if ($user->isTeacher() && $user->teacherProfile?->id === $session->teacher_id) return true;
        if ($user->isStudent() && $user->studentProfile?->id === $session->student_id) return true;
        return false;
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('memorization-sessions.create');
    }

    public function update(User $user, MemorizationSession $session): bool
    {
        if (! $user->hasPermissionTo('memorization-sessions.update')) return false;
        if ($user->isSuperAdmin() || $user->isAdmin()) return true;
        if ($user->isTeacher() && $user->teacherProfile?->id === $session->teacher_id) return true;
        return false;
    }
}
