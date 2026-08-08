<?php

namespace App\Http\Controllers;

use App\Models\MemorizationSession;
use App\Models\MemorizationAssignment;
use App\Http\Requests\StoreMemorizationSessionRequest;
use App\Http\Requests\UpdateMemorizationSessionRequest;
use Illuminate\Http\Request;

class MemorizationSessionController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', MemorizationSession::class);

        $query = MemorizationSession::with(['student', 'teacher', 'circle', 'surah', 'assignment']);

        $user = $request->user();
        if ($user->isTeacher() && $user->teacherProfile) {
            $query->where('teacher_id', $user->teacherProfile->id);
        } elseif ($user->isStudent() && $user->studentProfile) {
            $query->where('student_id', $user->studentProfile->id);
        }

        $sessions = $query->latest()->paginate(20);
        return view('memorization_sessions.index', compact('sessions'));
    }

    public function create(Request $request)
    {
        $this->authorize('create', MemorizationSession::class);
        $user = $request->user();
        $assignments = collect();
        $circles = collect();

        if ($user->isSuperAdmin() || $user->isAdmin()) {
            $circles = \App\Models\Circle::where('status', 'active')->get();
        } elseif ($user->isTeacher() && $user->teacherProfile) {
            $circles = $user->teacherProfile->circles()->where('status', 'active')->get();
            $assignments = MemorizationAssignment::forTeacher($user->teacherProfile->id)
                ->whereIn('status', ['assigned', 'in_progress'])->get();
        }

        return view('memorization_sessions.create', compact('circles', 'assignments'));
    }

    public function store(StoreMemorizationSessionRequest $request)
    {
        $data = $request->validated();
        $data['created_by'] = $request->user()->id;
        if (!isset($data['teacher_id'])) {
            $data['teacher_id'] = $request->user()->teacherProfile?->id;
        }

        $session = MemorizationSession::create($data);

        if ($session->memorization_assignment_id && $session->status === 'completed') {
            $assignment = $session->assignment;
            if ($assignment && $assignment->canTransitionTo('reviewed')) {
                $assignment->update([
                    'status' => 'reviewed',
                    'reviewed_at' => now(),
                    'completion_percent' => $session->total_score ?? 100,
                ]);
            }
        }

        return redirect()->route('memorization-sessions.show', $session)
            ->with('success', 'تم تسجيل الجلسة بنجاح');
    }

    public function show(MemorizationSession $session)
    {
        $this->authorize('view', $session);
        $session->load(['student', 'teacher', 'circle', 'surah', 'juz', 'assignment', 'mistakes', 'submission']);
        return view('memorization_sessions.show', compact('session'));
    }

    public function edit(MemorizationSession $session)
    {
        $this->authorize('update', $session);
        return view('memorization_sessions.edit', compact('session'));
    }

    public function update(UpdateMemorizationSessionRequest $request, MemorizationSession $session)
    {
        $session->update($request->validated());
        return redirect()->route('memorization-sessions.show', $session)
            ->with('success', 'تم تحديث الجلسة');
    }

    public function destroy(MemorizationSession $session)
    {
        $this->authorize('delete', $session);
        $session->delete();
        return redirect()->route('memorization-sessions.index')
            ->with('success', 'تم حذف الجلسة');
    }
}
