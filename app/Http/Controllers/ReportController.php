<?php

namespace App\Http\Controllers;

use App\Models\Circle;
use App\Models\StudentProfile;
use App\Models\StudentSubmission;
use App\Models\TeacherProfile;
use App\Models\Organization;
use App\Models\StudentProgress;
use App\Services\JuzProgressService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ReportController extends Controller
{
    public function studentReport(StudentProfile $student)
    {
        $student->load(['user', 'teacher.user', 'submissions.surahModel', 'submissions.juzModel', 'circles.circle']);

        $submissions = $student->submissions()->with(['surahModel', 'juzModel', 'circle'])->latest()->get();

        $approved = $submissions->filter(fn($s) => $s->score !== null && $s->score >= 70 && $s->surah_id);
        $progress = $approved->groupBy('surah_id')->map(function ($group) {
            return [
                'surah' => $group->first()->surah_display,
                'min_ayah' => $group->min('ayah_from'),
                'max_ayah' => $group->max('ayah_to'),
                'count' => $group->count(),
                'avg_score' => round($group->avg('score'), 1),
            ];
        })->values();

        $stats = [
            'total' => $submissions->count(),
            'pending' => $submissions->where('status', 'pending')->count(),
            'accepted' => $submissions->where('status', 'accepted')->count(),
            'needs_work' => $submissions->where('status', 'needs_work')->count(),
            'avg_score' => round($submissions->whereNotNull('score')->avg('score'), 1),
        ];

        return view('reports.student', compact('student', 'submissions', 'progress', 'stats'));
    }

    public function teacherReport(TeacherProfile $teacher)
    {
        $teacher->load(['user', 'circles']);

        $circleIds = \App\Models\CircleTeacher::where('teacher_id', $teacher->id)
            ->where('status', 'active')
            ->pluck('circle_id');

        $studentIds = \App\Models\CircleStudent::whereIn('circle_id', $circleIds)
            ->where('status', 'active')
            ->pluck('student_id')
            ->unique();

        $students = StudentProfile::whereIn('id', $studentIds)->with('user')->get();
        $totalStudents = $students->count();
        $totalSubmissions = StudentSubmission::whereIn('student_id', $studentIds)->count();
        $avgScore = StudentSubmission::whereIn('student_id', $studentIds)
            ->whereNotNull('score')->avg('score');
        $pendingReviews = StudentSubmission::whereIn('student_id', $studentIds)
            ->where('status', 'pending')->count();

        return view('reports.teacher', compact(
            'teacher', 'students', 'totalStudents',
            'totalSubmissions', 'avgScore', 'pendingReviews'
        ));
    }

    public function circleReport(Circle $circle)
    {
        $circle->load([
            'organization', 'juz',
            'circleTeachers.teacher.user',
            'circleStudents.student.user',
            'submissions'
        ]);

        $studentsProgress = collect();
        if ($circle->juz_id) {
            foreach ($circle->circleStudents as $cs) {
                if ($cs->student) {
                    $studentsProgress[$cs->student->id] = JuzProgressService::calculate(
                        $cs->student->id, $circle->juz_id, $circle->id
                    );
                }
            }
        }

        $totalSubmissions = $circle->submissions->count();
        $pendingSubmissions = $circle->submissions->where('status', 'pending')->count();
        $avgScore = round($circle->submissions->whereNotNull('score')->avg('score'), 1);

        return view('reports.circle', compact(
            'circle', 'studentsProgress', 'totalSubmissions',
            'pendingSubmissions', 'avgScore'
        ));
    }

    public function organizationReport(Organization $organization)
    {
        $circles = Circle::where('organization_id', $organization->id)->withCount(['circleStudents', 'submissions'])->get();
        $totalStudents = $circles->sum('circle_students_count');
        $totalSubmissions = $circles->sum('submissions_count');
        $totalCircles = $circles->count();

        return view('reports.organization', compact(
            'organization', 'circles', 'totalStudents',
            'totalSubmissions', 'totalCircles'
        ));
    }

    public function index()
    {
        return view('reports.index');
    }
}
