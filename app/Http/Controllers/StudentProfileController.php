<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\StudentProfile;
use App\Models\TeacherProfile;
use App\Actions\Students\CreateStudentAction;
use App\Actions\Students\UpdateStudentAction;
use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use Illuminate\Http\Request;

class StudentProfileController extends Controller
{
    /* =========================
        INDEX
    ========================== */
    public function index()
    {
        $students = StudentProfile::with(['user','teacher'])
            ->latest()
            ->paginate(20);

        return view('students.index', compact('students'));
    }

    /* =========================
        CREATE
    ========================== */
    public function create()
    {
        $users    = User::orderBy('email')->get();
        $teachers = TeacherProfile::select('teacher_profiles.*')
            ->join('users', 'users.id', '=', 'teacher_profiles.user_id')
            ->with('user')
            ->orderBy('users.email')
            ->get();

        return view('students.create', compact('users','teachers'));
    }

    /* =========================
        STORE
    ========================== */
    public function store(StoreStudentRequest $request, CreateStudentAction $action)
    {
        $student = $action->execute($request->validated(), $request->file('photo'));

        return redirect()
            ->route('students.index')
            ->with('success', 'تم إضافة الطالب بنجاح');
    }

    /* =========================
        SHOW
    ========================== */
    public function show(StudentProfile $student)
    {
        $student->load(['user','teacher.user','submissions.surahModel','submissions.juzModel']);

        // جلب كل التسجيلات
        $submissions = $student->submissions()->with(['surahModel','juzModel'])->latest()->get();

        // حساب التقدم من التسجيلات المعتمدة (درجة >= 70)
        $approved = $submissions->filter(fn($s) => $s->score !== null && $s->score >= 70 && $s->surah_id);
        $progress = $approved->groupBy('surah_id')->map(function ($group) {
            return [
                'surah' => $group->first()->surah_display,
                'min_ayah' => $group->min('ayah_from') ?? $group->min('ayah'),
                'max_ayah' => $group->max('ayah_to') ?? $group->max('ayah'),
                'count' => $group->count(),
                'avg_score' => round($group->avg('score'), 1),
            ];
        })->values();

        return view('students.show', compact('student', 'submissions', 'progress'));
    }

    /* =========================
        EDIT
    ========================== */
    public function edit(StudentProfile $student)
    {
        $users    = User::orderBy('email')->get();
        $teachers = TeacherProfile::select('teacher_profiles.*')
            ->join('users', 'users.id', '=', 'teacher_profiles.user_id')
            ->with('user')
            ->orderBy('users.email')
            ->get();

        return view('students.edit', compact('student','users','teachers'));
    }

    /* =========================
        UPDATE
    ========================== */
    public function update(UpdateStudentRequest $request, StudentProfile $student, UpdateStudentAction $action)
    {
        $action->execute($student, $request->validated(), $request->file('photo'));

        return redirect()
            ->route('students.show', $student)
            ->with('success', 'تم تحديث بيانات الطالب بنجاح');
    }

    /* =========================
        DESTROY (ARCHIVE)
    ========================== */
    public function destroy(StudentProfile $student)
    {
        $student->update(['status' => 'archived']);

        return redirect()
            ->route('students.index')
            ->with('success', 'تم أرشفة الطالب');
    }  
}

