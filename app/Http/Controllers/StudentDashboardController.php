<?php

namespace App\Http\Controllers;

use App\Models\Circle;
use App\Models\Surah;
use App\Models\MemorizationAssignment;
use App\Models\MemorizationSession;
use App\Models\CircleSession;
use App\Models\AttendanceRecord;
use App\Actions\Circles\JoinCircleAction;
use App\Services\JuzProgressService;
use App\Queries\Memorization\MemorizationAssignmentQuery;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StudentDashboardController extends Controller
{
    /**
     * Student Dashboard Home
     */
    public function dashboard(): View
    {
        $student = auth()->user()->studentProfile;
        $circle = $student?->circle;

        // جلب جميع التسجيلات مع العلاقات
        $submissions = $student
            ? $student->submissions()->with(['surahModel','juzModel','circle'])->latest()->get()
            : collect();

        // حساب التقدم من التسجيلات المعتمدة (درجة >= 70)
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

        return view('student.dashboard', compact('student', 'circle', 'submissions', 'progress'));
    }

    /**
     * Student Submission Page — unified upload + list + progress
     */
    public function submissions(): View
    {
        $student = auth()->user()->studentProfile;
        $circle = $student?->circle;

        $submissions = $student
            ? $student->submissions()->with(['surahModel', 'juzModel', 'circle'])->latest()->get()
            : collect();

        // إحصائيات مختصرة
        $stats = [
            'total' => $submissions->count(),
            'pending' => $submissions->where('status', 'pending')->count(),
            'reviewed' => $submissions->whereIn('status', ['reviewed', 'accepted'])->count(),
            'needs_work' => $submissions->where('status', 'needs_work')->count(),
            'avg_score' => $submissions->whereNotNull('score')->avg('score'),
        ];

        // حساب التقدم لكل جزء مرتبطة بالحلقة
        $juzProgress = collect();
        if ($student && $circle && $circle->juz_id) {
            $juzProgress->push([
                'juz_id' => $circle->juz_id,
                'juz_name' => $circle->juz?->name ?? 'الجزء ' . $circle->juz_id,
                'circle_name' => $circle->name,
                'progress' => JuzProgressService::calculate($student->id, $circle->juz_id, $circle->id),
            ]);
        }

        // أيضاً جلب أجزاء أخرى ربما رفع فيها الطالب تسجيلات
        $otherJuzIds = $submissions->pluck('juz_id')->filter()->unique()
            ->reject(fn($id) => $circle && $circle->juz_id == $id);
        foreach ($otherJuzIds as $juzId) {
            $juz = \App\Models\Juz::find($juzId);
            $juzProgress->push([
                'juz_id' => $juzId,
                'juz_name' => $juz?->name ?? 'الجزء ' . $juzId,
                'circle_name' => null,
                'progress' => JuzProgressService::calculate($student->id, $juzId),
            ]);
        }

        return view('student.submissions', compact('student', 'circle', 'submissions', 'stats', 'juzProgress'));
    }

    /**
     * Upload Recording Form
     */
    public function uploadForm(): View
    {
        $student = auth()->user()->studentProfile;
        $circle = $student?->circle;
        
        return view('student.recordings.record', compact('student', 'circle'));
    }

    /**
     * Recordings List
     */
    public function recordingsList(): View
    {
        $student = auth()->user()->studentProfile;
        $circle = $student?->circle;
        $submissions = $student
            ? $student->submissions()->with(['surahModel','juzModel','circle'])->latest()->get()
            : collect();

        // حساب التقدم من التسجيلات المعتمدة (درجة >= 70)
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

        return view('student.recordings.list', compact('student', 'circle', 'submissions', 'progress'));
    }

    /**
     * Circles Page
     */
    public function circles(): View
    {
        $student = auth()->user()->studentProfile;
        $currentCircle = $student?->circle;
        $enrolledCircles = $student?->circles()->with('circle')->get() ?? collect();
        $availableCircles = Circle::whereDoesntHave('students', function($q) use ($student) {
            $q->where('student_id', $student?->id);
        })->get();
        
        return view('student.circles', compact('student', 'currentCircle', 'enrolledCircles', 'availableCircles'));
    }

    /**
     * Progress Page
     */
    public function progress(): View
    {
        $student = auth()->user()->studentProfile;
        $circle = $student?->circle;
        $progress = $student?->latestProgress;
        
        return view('student.progress', compact('student', 'circle', 'progress'));
    }

    /**
     * Join a Circle
     */
    public function joinCircle(Circle $circle, JoinCircleAction $action): RedirectResponse
    {
        $student = auth()->user()->studentProfile;

        if (!$student) {
            return redirect()->back()->with('error', 'لم يتم العثور على ملف الطالب');
        }

        try {
            $action->execute($student, $circle);
        } catch (\RuntimeException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect()->back()->with('success', 'تم الانضمام إلى الحلقة بنجاح!');
    }

    /**
     * Student Assignments Page
     */
    public function assignments(Request $request, MemorizationAssignmentQuery $query)
    {
        $student = auth()->user()->studentProfile;
        if (!$student) {
            return redirect()->route('student.dashboard')->with('error', 'لم يتم العثور على ملف الطالب');
        }

        $assignments = MemorizationAssignment::forStudent($student->id)
            ->with(['surah', 'juz', 'circle', 'teacher.user'])
            ->orderByRaw("FIELD(status, 'assigned','in_progress','needs_revision','submitted','reviewed','completed','cancelled')")
            ->latest()
            ->paginate(20);

        return view('student.assignments', compact('assignments'));
    }

    /**
     * Student View Single Assignment
     */
    public function showAssignment(MemorizationAssignment $assignment)
    {
        $student = auth()->user()->studentProfile;
        if ($assignment->student_id !== $student?->id) {
            abort(403);
        }

        $assignment->load(['surah', 'juz', 'circle', 'teacher.user', 'submissions']);
        return view('student.assignment-show', compact('assignment'));
    }

    /**
     * Student Update Assignment Status
     */
    public function updateAssignmentStatus(Request $request, MemorizationAssignment $assignment)
    {
        $student = auth()->user()->studentProfile;
        if ($assignment->student_id !== $student?->id) {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:in_progress,submitted',
        ]);

        $allowed = ['assigned' => 'in_progress', 'in_progress' => 'submitted', 'needs_revision' => 'in_progress'];

        if (!isset($allowed[$assignment->status]) || $allowed[$assignment->status] !== $request->status) {
            return redirect()->back()->with('error', 'لا يمكن تغيير الحالة إلى ' . $request->status);
        }

        $data = ['status' => $request->status];
        if ($request->status === 'in_progress' && !$assignment->started_at) {
            $data['started_at'] = now();
        }
        if ($request->status === 'submitted' && !$assignment->submitted_at) {
            $data['submitted_at'] = now();
        }

        $assignment->update($data);

        return redirect()->back()->with('success', 'تم تحديث حالة المهمة');
    }
}
