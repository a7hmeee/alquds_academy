<?php

namespace App\Policies;

use App\Models\User;
use App\Models\TeacherProfile;

class TeacherProfilePolicy
{
    public function view(User $user, TeacherProfile $teacher): bool
    {
        return $user->hasAnyRole(['super admin', 'admin']) || $user->id === $teacher->user_id;
    }

    public function update(User $user, TeacherProfile $teacher): bool
    {
        return $user->hasAnyRole(['super admin', 'admin']) || $user->id === $teacher->user_id;
    }
}
