<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Circle;
use App\Models\StudentProfile;
use App\Models\TeacherProfile;
use App\Models\StudentSubmission;
use App\Models\StudentProgress;
use App\Models\Organization;
use App\Models\MemorizationAssignment;
use App\Models\MemorizationSession;
use App\Models\QuranExam;
use App\Models\RevisionPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ExecutiveDashboardController extends Controller
{
    public function index()
    {
        $stats = Cache::remember('executive.dashboard', 300, function () {
            $totalStudents = StudentProfile::count();
            $totalTeachers = TeacherProfile::count();
            $totalCircles = Circle::count();
            $totalSubmissions = StudentSubmission::count();
            $totalOrganizations = Organization::count();
            $totalUsers = User::count();

            $pendingSubmissions = StudentSubmission::where('status', 'pending')->count();
            $reviewedSubmissions = StudentSubmission::whereIn('status', ['reviewed', 'accepted'])->count();
            $needsWorkSubmissions = StudentSubmission::where('status', 'needs_work')->count();

            $avgScore = StudentSubmission::whereNotNull('score')->avg('score');

            $activeCircles = Circle::where('status', 'active')->count();
            $activeStudents = StudentProfile::where('status', 'active')->count();
            $activeTeachers = TeacherProfile::where('status', 'active')->count();

            $topStudents = StudentSubmission::selectRaw('student_id, AVG(score) as avg_score, COUNT(*) as total')
                ->whereNotNull('score')
                ->groupBy('student_id')
                ->orderByDesc('avg_score')
                ->take(10)
                ->with('student.user')
                ->get();

            $topCircles = Circle::withCount(['submissions', 'circleStudents'])
                ->orderByDesc('submissions_count')
                ->take(10)
                ->get();

            $progressPerJuz = StudentProgress::selectRaw('juz_id, COUNT(*) as total')
                ->whereNotNull('juz_id')
                ->groupBy('juz_id')
                ->orderBy('juz_id')
                ->with('juz')
                ->get();

            $recentActivity = StudentSubmission::with(['student.user', 'circle'])
                ->latest()
                ->take(20)
                ->get();

            $submissionsByDay = StudentSubmission::selectRaw('DATE(created_at) as date, COUNT(*) as count')
                ->where('created_at', '>=', now()->subDays(30))
                ->groupBy('date')
                ->orderBy('date')
                ->get();

            $statusDistribution = [
                'pending' => $pendingSubmissions,
                'reviewed' => $reviewedSubmissions,
                'needs_work' => $needsWorkSubmissions,
                'total' => $totalSubmissions,
            ];

            $totalAssignments = MemorizationAssignment::count();
            $pendingAssignments = MemorizationAssignment::whereIn('status', ['assigned', 'in_progress'])->count();
            $totalSessions = MemorizationSession::count();
            $totalExams = QuranExam::count();
            $activeRevisionPlans = RevisionPlan::where('status', 'active')->count();

            return compact(
                'totalStudents', 'totalTeachers', 'totalCircles',
                'totalSubmissions', 'totalOrganizations', 'totalUsers',
                'pendingSubmissions', 'reviewedSubmissions', 'needsWorkSubmissions',
                'avgScore', 'activeCircles', 'activeStudents', 'activeTeachers',
                'topStudents', 'topCircles', 'progressPerJuz',
                'recentActivity', 'submissionsByDay', 'statusDistribution',
                'totalAssignments', 'pendingAssignments', 'totalSessions',
                'totalExams', 'activeRevisionPlans'
            );
        });

        return view('dashboard', $stats);
    }

    public function refreshCache()
    {
        Cache::forget('executive.dashboard');
        return redirect()->route('admin.dashboard')->with('success', 'تم تحديث الإحصائيات');
    }
}
