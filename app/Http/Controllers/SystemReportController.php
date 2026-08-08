<?php

namespace App\Http\Controllers;

use App\Models\Circle;
use App\Models\StudentProfile;
use App\Models\StudentSubmission;
use App\Queries\Reports\SystemReportQuery;

class SystemReportController extends Controller
{
    public function index(SystemReportQuery $reportQuery)
    {
        $now = now();

        $general = $reportQuery->generalStats();

        $totalCircles = $general['total_circles'];
        $activeCircles = $general['active_circles'];
        $totalStudents = $general['total_students'];
        $activeStudents = $general['active_students'];
        $totalTeachers = $general['total_teachers'];
        $totalOrganizations = $general['total_orgs'];
        $totalSubmissions = $general['total_subs'];
        $pendingSubmissions = $general['pending_subs'];
        $acceptedSubmissions = $general['accepted_subs'];
        $needsWorkSubmissions = $general['needs_work_subs'];
        $totalProgressRecords = $general['total_progress'];
        $avgScore = $general['avg_score'];

        $engagementRate = $totalStudents > 0
            ? round(($totalSubmissions / max($totalStudents, 1)) * 100, 1)
            : 0;

        $submissionsByMonth = StudentSubmission::where('created_at', '>=', $now->copy()->subMonths(6))
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(*) as count")
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $circlesData = $reportQuery->circlesData();
        $weakCircles = $circlesData->filter(fn($c) => $c['total_submissions'] < 5 && $c['active_students'] > 0);
        $strongCircles = $circlesData->filter(fn($c) => $c['avg_score'] !== '—' && $c['avg_score'] >= 80);

        $studentsData = $reportQuery->studentsData();
        $topStudents = $studentsData->filter(fn($s) => $s['avg_score'] !== '—' && $s['avg_score'] >= 85)->sortByDesc('avg_score')->take(10);
        $atRiskStudents = $studentsData->filter(fn($s) => $s['needs_assistance'] || ($s['total_submissions'] > 0 && $s['avg_score'] !== '—' && $s['avg_score'] < 50));
        $inactiveStudents = $studentsData->filter(fn($s) => $s['total_submissions'] === 0);

        $teachersData = $reportQuery->teachersData();

        // ── Submissions Report ──
        $submissionsByStatus = StudentSubmission::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status');

        $submissionsByDay = StudentSubmission::where('created_at', '>=', $now->copy()->subDays(30))
            ->selectRaw("DATE(created_at) as day, COUNT(*) as count")
            ->groupBy('day')
            ->orderBy('day')
            ->get();

        $recentSubmissions = StudentSubmission::with(['student.user', 'circle', 'surahModel'])
            ->latest()
            ->take(20)
            ->get()
            ->map(fn($s) => [
                'student' => $s->student?->full_name ?? $s->student?->user?->name ?? '—',
                'circle' => $s->circle?->name ?? '—',
                'surah' => $s->surah_display ?? '—',
                'score' => $s->score,
                'status' => $s->status,
                'date' => $s->created_at?->format('Y-m-d') ?? '—',
            ]);

        // ── Issues / Alerts ──
        $issues = [];

        // Orphan students (no circle)
        $orphanStudents = StudentProfile::whereDoesntHave('circles', fn($q) => $q->where('status', 'active'))->count();
        if ($orphanStudents > 0) {
            $issues[] = [
                'type' => 'warning',
                'title' => 'طلاب بدون حلقات',
                'detail' => "{$orphanStudents} طالب غير مسجلين في أي حلقة نشطة",
            ];
        }

        // Circles without teachers
        $circlesWithoutTeachers = Circle::where('status', 'active')
            ->whereDoesntHave('circleTeachers', fn($q) => $q->where('status', 'active'))
            ->count();
        if ($circlesWithoutTeachers > 0) {
            $issues[] = [
                'type' => 'danger',
                'title' => 'حلقات بدون معلمين',
                'detail' => "{$circlesWithoutTeachers} حلقة نشطة بدون أي معلم معيّن",
            ];
        }

        // Circles without students
        $circlesWithoutStudents = Circle::where('status', 'active')
            ->whereDoesntHave('circleStudents', fn($q) => $q->where('status', 'active'))
            ->count();
        if ($circlesWithoutStudents > 0) {
            $issues[] = [
                'type' => 'info',
                'title' => 'حلقات فارغة',
                'detail' => "{$circlesWithoutStudents} حلقة نشطة بدون طلاب مسجلين",
            ];
        }

        // Old pending submissions (>7 days)
        $oldPending = StudentSubmission::where('status', 'pending')
            ->where('created_at', '<', $now->copy()->subDays(7))
            ->count();
        if ($oldPending > 0) {
            $issues[] = [
                'type' => 'danger',
                'title' => 'تسليمات معلقة قديمة',
                'detail' => "{$oldPending} تسجيل في انتظار المراجعة منذ أكثر من 7 أيام",
            ];
        }

        // Students at risk
        if ($atRiskStudents->count() > 0) {
            $issues[] = [
                'type' => 'warning',
                'title' => 'طلاب في خطر',
                'detail' => "{$atRiskStudents->count()} طالب يحتاجون مساعدة (درجة أقل من 50 أو علامة احتياج)",
            ];
        }

        // Empty circles
        if ($circlesWithoutStudents > 0) {
            $issues[] = [
                'type' => 'info',
                'title' => 'بيانات مفقودة',
                'detail' => 'بعض الحلقات ليس لها طلاب أو معلمين — قد تحتاج مراجعة',
            ];
        }

        // ── AI Insights ──
        $insights = [];

        // Submission trend
        if ($submissionsByMonth->count() >= 2) {
            $lastMonth = $submissionsByMonth->last()->count ?? 0;
            $prevMonth = $submissionsByMonth->slice(-2, 1)->first()->count ?? 0;
            if ($prevMonth > 0) {
                $change = round((($lastMonth - $prevMonth) / $prevMonth) * 100, 1);
                if ($change > 20) {
                    $insights[] = ['type' => 'success', 'text' => "نشاط التسليمات يرتفع بنسبة {$change}% مقارنة بالشهر السابق"];
                } elseif ($change < -20) {
                    $insights[] = ['type' => 'warning', 'text' => "نشاط التسليمات ينخفض بنسبة {$change}% مقارنة بالشهر السابق"];
                }
            }
        }

        // Score distribution
        if ($avgScore >= 80) {
            $insights[] = ['type' => 'success', 'text' => "متوسط الدرجات عام ومرتفع ({$avgScore}/100) — أداء ممتاز"];
        } elseif ($avgScore >= 60) {
            $insights[] = ['type' => 'info', 'text' => "متوسط الدرجات مقبول ({$avgScore}/100) — يمكن تحسين"];
        } else {
            $insights[] = ['type' => 'warning', 'text' => "متوسط الدرجات منخفض ({$avgScore}/100) — يحتاج تدخل"];
        }

        // Active vs inactive
        $inactiveRate = $totalStudents > 0 ? round(($inactiveStudents->count() / $totalStudents) * 100, 1) : 0;
        if ($inactiveRate > 30) {
            $insights[] = ['type' => 'danger', 'text' => "{$inactiveRate}% من الطلاب لم يرفعوا أي تسجيلات بعد"];
        }

        // Teacher workload
        $overloadedTeachers = $teachersData->filter(fn($t) => $t['pending_count'] > 10);
        if ($overloadedTeachers->count() > 0) {
            $insights[] = ['type' => 'warning', 'text' => "{$overloadedTeachers->count()} معلم لديهم أكثر من 10 تسجيلات في انتظار المراجعة"];
        }

        // Capacity alerts
        $fullCircles = $circlesData->filter(fn($c) => $c['capacity'] !== '∞' && $c['active_students'] >= $c['capacity']);
        if ($fullCircles->count() > 0) {
            $insights[] = ['type' => 'info', 'text' => "{$fullCircles->count()} حلقة مكتملة العدد — قد تحتاج فتح حلقات جديدة"];
        }

        return view('reports.system', compact(
            'now',
            'totalCircles', 'activeCircles', 'totalStudents', 'activeStudents',
            'totalTeachers', 'totalOrganizations', 'totalSubmissions', 'pendingSubmissions',
            'acceptedSubmissions', 'needsWorkSubmissions', 'totalProgressRecords',
            'avgScore', 'engagementRate',
            'submissionsByMonth', 'circlesData', 'weakCircles', 'strongCircles',
            'studentsData', 'topStudents', 'atRiskStudents', 'inactiveStudents',
            'teachersData', 'submissionsByStatus', 'submissionsByDay', 'recentSubmissions',
            'issues', 'insights'
        ));
    }
}
