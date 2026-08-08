<?php

namespace App\Queries\Reports;

use App\Models\Circle;
use App\Models\CircleStudent;
use App\Models\StudentProfile;
use App\Models\TeacherProfile;
use App\Models\StudentSubmission;
use App\Models\StudentProgress;
use App\Models\Organization;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class SystemReportQuery
{
    public function generalStats(): array
    {
        return [
            'total_circles'    => Circle::count(),
            'active_circles'   => Circle::where('status', 'active')->count(),
            'total_students'   => StudentProfile::count(),
            'active_students'  => StudentProfile::where('status', 'active')->count(),
            'total_teachers'   => TeacherProfile::count(),
            'total_orgs'       => Organization::count(),
            'total_subs'       => StudentSubmission::count(),
            'pending_subs'     => StudentSubmission::where('status', 'pending')->count(),
            'accepted_subs'    => StudentSubmission::where('status', 'accepted')->count(),
            'needs_work_subs'  => StudentSubmission::where('status', 'needs_work')->count(),
            'total_progress'   => StudentProgress::count(),
            'avg_score'        => round(StudentSubmission::whereNotNull('score')->avg('score') ?? 0, 1),
        ];
    }

    public function circlesData(): Collection
    {
        $circleIds = Circle::pluck('id');

        $activeStudentCounts = CircleStudent::where('status', 'active')
            ->whereIn('circle_id', $circleIds)
            ->groupBy('circle_id')
            ->selectRaw('circle_id, COUNT(*) as count')
            ->pluck('count', 'circle_id');

        $submissionStats = StudentSubmission::whereIn('circle_id', $circleIds)
            ->groupBy('circle_id')
            ->selectRaw('circle_id, COUNT(*) as total, AVG(score) as avg_score, SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as pending', ['pending'])
            ->get()
            ->keyBy('circle_id');

        return Circle::with(['organization', 'juz'])
            ->get()
            ->map(function ($circle) use ($activeStudentCounts, $submissionStats) {
                $stats = $submissionStats->get($circle->id);
                $totalSubs = $stats?->total ?? 0;
                $avgScore = $stats?->avg_score;
                $pendingSubs = $stats?->pending ?? 0;

                return [
                    'id' => $circle->id,
                    'name' => $circle->name,
                    'status' => $circle->status,
                    'type' => $circle->type,
                    'organization' => $circle->organization?->name ?? '—',
                    'juz' => $circle->juz?->name ?? '—',
                    'capacity' => $circle->capacity ?? '∞',
                    'active_students' => $activeStudentCounts->get($circle->id, 0),
                    'total_submissions' => $totalSubs,
                    'avg_score' => $avgScore ? round($avgScore, 1) : '—',
                    'pending_submissions' => $pendingSubs,
                ];
            });
    }

    public function studentsData(): Collection
    {
        $studentIds = StudentProfile::pluck('id');

        $submissionStats = StudentSubmission::whereIn('student_id', $studentIds)
            ->groupBy('student_id')
            ->selectRaw('student_id, COUNT(*) as total, AVG(score) as avg_score, SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as pending, SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as accepted', ['pending', 'accepted'])
            ->get()
            ->keyBy('student_id');

        $circleCounts = CircleStudent::where('status', 'active')
            ->whereIn('student_id', $studentIds)
            ->groupBy('student_id')
            ->selectRaw('student_id, COUNT(*) as count')
            ->pluck('count', 'student_id');

        return StudentProfile::with('user')
            ->get()
            ->map(function ($student) use ($submissionStats, $circleCounts) {
                $stats = $submissionStats->get($student->id);
                $totalSubs = $stats?->total ?? 0;
                $avgScore = $stats?->avg_score;

                return [
                    'id' => $student->id,
                    'name' => $student->full_name ?? $student->user?->name ?? '—',
                    'email' => $student->user?->email ?? '—',
                    'status' => $student->status ?? '—',
                    'memorization_level' => $student->memorization_level ?? '—',
                    'total_submissions' => $totalSubs,
                    'avg_score' => $avgScore ? round($avgScore, 1) : '—',
                    'pending_submissions' => $stats?->pending ?? 0,
                    'accepted_submissions' => $stats?->accepted ?? 0,
                    'circle_count' => $circleCounts->get($student->id, 0),
                    'needs_assistance' => $student->needs_assistance ?? false,
                ];
            });
    }

    public function teachersData(): Collection
    {
        $teacherIds = TeacherProfile::pluck('id');

        $circleData = \App\Models\CircleTeacher::where('status', 'active')
            ->whereIn('teacher_id', $teacherIds)
            ->get()
            ->groupBy('teacher_id');

        $allCircleIds = $circleData->flatten(1)->pluck('circle_id')->unique()->values();

        $studentCounts = CircleStudent::where('status', 'active')
            ->whereIn('circle_id', $allCircleIds)
            ->groupBy('circle_id')
            ->selectRaw('circle_id, COUNT(*) as count')
            ->pluck('count', 'circle_id');

        $submissionTotals = StudentSubmission::whereIn('circle_id', $allCircleIds)
            ->groupBy('circle_id')
            ->selectRaw('circle_id, COUNT(*) as total')
            ->pluck('total', 'circle_id');

        $pendingCounts = StudentSubmission::where('status', 'pending')
            ->whereIn('circle_id', $allCircleIds)
            ->groupBy('circle_id')
            ->selectRaw('circle_id, COUNT(*) as count')
            ->pluck('count', 'circle_id');

        $avgScores = StudentSubmission::whereNotNull('score')
            ->whereIn('circle_id', $allCircleIds)
            ->groupBy('circle_id')
            ->selectRaw('circle_id, AVG(score) as avg')
            ->pluck('avg', 'circle_id');

        $progressCounts = StudentProgress::whereIn('circle_id', $allCircleIds)
            ->groupBy('circle_id')
            ->selectRaw('circle_id, COUNT(*) as count')
            ->pluck('count', 'circle_id');

        return TeacherProfile::with('user')
            ->get()
            ->map(function ($teacher) use ($circleData, $studentCounts, $submissionTotals, $pendingCounts, $avgScores, $progressCounts) {
                $activeCircles = $circleData->get($teacher->id, collect());
                $circleIds = $activeCircles->pluck('circle_id');

                $studentCount = $circleIds->sum(fn($id) => $studentCounts->get($id, 0));
                $submissionCount = $circleIds->sum(fn($id) => $submissionTotals->get($id, 0));
                $pendingCount = $circleIds->sum(fn($id) => $pendingCounts->get($id, 0));
                $weightedAvg = $this->weightedAverage($circleIds, $avgScores, $submissionTotals);
                $progressCount = $circleIds->sum(fn($id) => $progressCounts->get($id, 0));

                return [
                    'id' => $teacher->id,
                    'name' => $teacher->user?->name ?? '—',
                    'email' => $teacher->user?->email ?? '—',
                    'specialization' => $teacher->specialization ?? '—',
                    'circle_count' => $activeCircles->count(),
                    'student_count' => $studentCount,
                    'submission_count' => $submissionCount,
                    'pending_count' => $pendingCount,
                    'avg_score' => $weightedAvg !== null ? round($weightedAvg, 1) : '—',
                    'progress_records' => $progressCount,
                ];
            });
    }

    private function weightedAverage(Collection $circleIds, Collection $avgScores, Collection $totals): ?float
    {
        $totalWeight = 0;
        $weightedSum = 0;
        foreach ($circleIds as $id) {
            $avg = $avgScores->get($id);
            $count = $totals->get($id, 0);
            if ($avg !== null && $count > 0) {
                $weightedSum += $avg * $count;
                $totalWeight += $count;
            }
        }
        return $totalWeight > 0 ? $weightedSum / $totalWeight : null;
    }
}
