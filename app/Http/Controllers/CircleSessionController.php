<?php

namespace App\Http\Controllers;

use App\Models\CircleSession;
use App\Models\AttendanceRecord;
use App\Models\Circle;
use Illuminate\Http\Request;

class CircleSessionController extends Controller
{
    public function index(Circle $circle)
    {
        $sessions = $circle->circleSessions ?? $circle->sessions()
            ->withCount('attendanceRecords')
            ->latest('session_date')
            ->paginate(20);

        return view('circle_sessions.index', compact('circle', 'sessions'));
    }

    public function create(Circle $circle)
    {
        return view('circle_sessions.create', compact('circle'));
    }

    public function store(Request $request, Circle $circle)
    {
        $data = $request->validate([
            'title' => 'nullable|string|max:255',
            'session_date' => 'required|date',
            'starts_at' => 'nullable|date_format:H:i',
            'ends_at' => 'nullable|date_format:H:i|after:starts_at',
            'session_type' => 'nullable|in:regular,exam,review,event',
            'notes' => 'nullable|string|max:2000',
        ]);

        $teacherId = null;
        if ($request->user()->isTeacher() && $request->user()->teacherProfile) {
            $teacherId = $request->user()->teacherProfile->id;
        }

        $session = CircleSession::create([
            'circle_id' => $circle->id,
            'teacher_id' => $teacherId ?? $circle->primaryTeacher()?->id,
            'title' => $data['title'] ?? null,
            'session_date' => $data['session_date'],
            'starts_at' => $data['starts_at'] ?? null,
            'ends_at' => $data['ends_at'] ?? null,
            'session_type' => $data['session_type'] ?? 'regular',
            'status' => 'completed',
            'notes' => $data['notes'] ?? null,
            'created_by' => $request->user()->id,
        ]);

        return redirect()->route('circle-sessions.attendance', [$circle, $session])
            ->with('success', 'تم إنشاء الجلسة، سجل الحضور الآن');
    }

    public function attendance(Circle $circle, CircleSession $session)
    {
        $students = $circle->students()->wherePivot('status', 'active')->get();
        $records = $session->attendanceRecords()->get()->keyBy('student_id');
        return view('circle_sessions.attendance', compact('circle', 'session', 'students', 'records'));
    }

    public function saveAttendance(Request $request, Circle $circle, CircleSession $session)
    {
        $data = $request->validate([
            'attendance' => 'required|array',
            'attendance.*.status' => 'required|in:present,absent,late,excused',
            'attendance.*.excuse' => 'nullable|string|max:500',
            'attendance.*.note' => 'nullable|string|max:1000',
        ]);

        foreach ($data['attendance'] as $studentId => $record) {
            AttendanceRecord::updateOrCreate(
                [
                    'circle_session_id' => $session->id,
                    'student_id' => $studentId,
                ],
                [
                    'status' => $record['status'],
                    'excuse' => $record['excuse'] ?? null,
                    'note' => $record['note'] ?? null,
                    'recorded_by' => $request->user()->id,
                ]
            );
        }

        return redirect()->route('circle-sessions.show', [$circle, $session])
            ->with('success', 'تم تسجيل الحضور');
    }

    public function show(Circle $circle, CircleSession $session)
    {
        $session->load(['attendanceRecords.student']);
        $stats = [
            'present' => $session->attendanceRecords->where('status', 'present')->count(),
            'absent' => $session->attendanceRecords->where('status', 'absent')->count(),
            'late' => $session->attendanceRecords->where('status', 'late')->count(),
            'excused' => $session->attendanceRecords->where('status', 'excused')->count(),
        ];
        return view('circle_sessions.show', compact('circle', 'session', 'stats'));
    }
}
