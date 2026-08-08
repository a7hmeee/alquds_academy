<?php

namespace App\Queries\Memorization;

use App\Models\MemorizationAssignment;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

class MemorizationAssignmentQuery
{
    public function forUser(User $user, array $filters = []): LengthAwarePaginator
    {
        $query = MemorizationAssignment::with(['student', 'surah', 'juz', 'circle']);

        if ($user->isSuperAdmin() || $user->isAdmin()) {
            // all
        } elseif ($user->isTeacher() && $user->teacherProfile) {
            $query->where('teacher_id', $user->teacherProfile->id);
        } elseif ($user->isStudent() && $user->studentProfile) {
            $query->where('student_id', $user->studentProfile->id);
        } else {
            return MemorizationAssignment::whereRaw('1=0')->paginate(20);
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['circle_id'])) {
            $query->where('circle_id', $filters['circle_id']);
        }

        if (!empty($filters['assignment_type'])) {
            $query->where('assignment_type', $filters['assignment_type']);
        }

        if (!empty($filters['student_id'])) {
            $query->where('student_id', $filters['student_id']);
        }

        $sortField = $filters['sort'] ?? 'created_at';
        $sortDir = $filters['dir'] ?? 'desc';
        $query->orderBy($sortField, $sortDir);

        return $query->paginate($filters['per_page'] ?? 20);
    }

    public function todayByTeacher(int $teacherId): \Illuminate\Database\Eloquent\Collection
    {
        return MemorizationAssignment::with(['student', 'surah'])
            ->where('teacher_id', $teacherId)
            ->whereIn('status', ['assigned', 'in_progress'])
            ->whereDate('due_at', today())
            ->orderBy('priority', 'desc')
            ->get();
    }

    public function overdueByTeacher(int $teacherId): \Illuminate\Database\Eloquent\Collection
    {
        return MemorizationAssignment::with(['student', 'surah'])
            ->where('teacher_id', $teacherId)
            ->whereIn('status', ['assigned', 'in_progress'])
            ->where('due_at', '<', now())
            ->orderBy('due_at')
            ->get();
    }
}
