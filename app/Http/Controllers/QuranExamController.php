<?php

namespace App\Http\Controllers;

use App\Models\QuranExam;
use App\Models\QuranExamResult;
use App\Models\Circle;
use Illuminate\Http\Request;

class QuranExamController extends Controller
{
    public function index(Request $request)
    {
        $query = QuranExam::with(['circle', 'teacher']);

        $user = $request->user();
        if ($user->isTeacher() && $user->teacherProfile) {
            $query->where('teacher_id', $user->teacherProfile->id);
        }

        $exams = $query->latest()->paginate(20);
        return view('quran_exams.index', compact('exams'));
    }

    public function create(Request $request)
    {
        $user = $request->user();
        $circles = collect();
        if ($user->isSuperAdmin() || $user->isAdmin()) {
            $circles = Circle::where('status', 'active')->get();
        } elseif ($user->isTeacher() && $user->teacherProfile) {
            $circles = $user->teacherProfile->circles()->where('status', 'active')->get();
        }
        return view('quran_exams.create', compact('circles'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'circle_id' => 'required|exists:circles,id',
            'title' => 'required|string|max:255',
            'exam_type' => 'required|in:surah,juz,multiple_surahs,review,oral,tajweed,random',
            'surah_id' => 'nullable|exists:surahs,id',
            'juz_id' => 'nullable|exists:juz,id',
            'total_score' => 'nullable|integer|min:1|max:1000',
            'passing_score' => 'nullable|integer|min:1|max:1000',
            'exam_date' => 'required|date',
            'instructions' => 'nullable|string|max:5000',
        ]);

        $teacherId = $request->user()->teacherProfile?->id;

        $exam = QuranExam::create([
            'circle_id' => $data['circle_id'],
            'teacher_id' => $teacherId,
            'title' => $data['title'],
            'exam_type' => $data['exam_type'],
            'surah_id' => $data['surah_id'] ?? null,
            'juz_id' => $data['juz_id'] ?? null,
            'total_score' => $data['total_score'] ?? 100,
            'passing_score' => $data['passing_score'] ?? 70,
            'exam_date' => $data['exam_date'],
            'instructions' => $data['instructions'] ?? null,
            'created_by' => $request->user()->id,
        ]);

        return redirect()->route('quran-exams.show', $exam)
            ->with('success', 'تم إنشاء الاختبار');
    }

    public function show(QuranExam $exam)
    {
        $exam->load(['circle', 'teacher', 'surah', 'juz', 'results.student']);
        return view('quran_exams.show', compact('exam'));
    }

    public function saveResult(Request $request, QuranExam $exam)
    {
        $data = $request->validate([
            'student_id' => 'required|exists:student_profiles,id',
            'score' => 'nullable|integer|min:0|max:' . $exam->total_score,
            'tajweed_score' => 'nullable|integer|min:0|max:100',
            'memorization_score' => 'nullable|integer|min:0|max:100',
            'teacher_notes' => 'nullable|string|max:2000',
            'status' => 'required|in:completed,absent',
        ]);

        $score = $data['score'] ?? 0;
        $percentage = $exam->total_score > 0 ? round(($score / $exam->total_score) * 100, 2) : 0;
        $passed = $percentage >= $exam->passing_score;

        QuranExamResult::updateOrCreate(
            [
                'quran_exam_id' => $exam->id,
                'student_id' => $data['student_id'],
            ],
            [
                'score' => $data['score'] ?? null,
                'percentage' => $percentage,
                'passed' => $data['status'] === 'completed' ? $passed : null,
                'tajweed_score' => $data['tajweed_score'] ?? null,
                'memorization_score' => $data['memorization_score'] ?? null,
                'teacher_notes' => $data['teacher_notes'] ?? null,
                'status' => $data['status'],
                'completed_at' => $data['status'] === 'completed' ? now() : null,
            ]
        );

        return redirect()->back()->with('success', 'تم حفظ النتيجة');
    }
}
