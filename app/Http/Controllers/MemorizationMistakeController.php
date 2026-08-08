<?php

namespace App\Http\Controllers;

use App\Models\MemorizationMistake;
use App\Models\MemorizationSession;
use Illuminate\Http\Request;

class MemorizationMistakeController extends Controller
{
    public function index(Request $request)
    {
        $query = MemorizationMistake::with(['student', 'surah', 'session']);

        $user = $request->user();
        if ($user->isStudent() && $user->studentProfile) {
            $query->where('student_id', $user->studentProfile->id);
        } elseif ($user->isTeacher() && $user->teacherProfile) {
            $query->whereHas('session', fn($q) => $q->where('teacher_id', $user->teacherProfile->id));
        }

        $mistakes = $query->latest()->paginate(20);
        return view('memorization_mistakes.index', compact('mistakes'));
    }

    public function store(Request $request, MemorizationSession $session)
    {
        $this->authorize('create', MemorizationMistake::class);

        $data = $request->validate([
            'ayah_number' => 'required|integer|min:0',
            'mistake_type' => 'required|in:memorization,tajweed,haraka,madd,ghunnah,makhraj,waqf_ibtida,omission,repetition,hesitation,other',
            'severity' => 'nullable|in:minor,moderate,major,critical',
            'word_text' => 'nullable|string|max:500',
            'correct_text' => 'nullable|string|max:500',
            'teacher_note' => 'nullable|string|max:2000',
        ]);

        $data['memorization_session_id'] = $session->id;
        $data['student_id'] = $session->student_id;
        $data['surah_id'] = $session->surah_id;
        $data['severity'] = $data['severity'] ?? 'minor';

        MemorizationMistake::create($data);

        return redirect()->route('memorization-sessions.show', $session)
            ->with('success', 'تم تسجيل الخطأ');
    }

    public function resolve(MemorizationMistake $mistake)
    {
        $mistake->update([
            'is_resolved' => true,
            'resolved_at' => now(),
        ]);

        return redirect()->back()->with('success', 'تم تعليم الخطأ كمحلول');
    }
}
