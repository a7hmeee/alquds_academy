<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ParentDashboardController extends Controller
{
    public function dashboard(Request $request)
    {
        $parent = $request->user()->parentProfile;
        $students = $parent?->students()->with(['user', 'latestProgress', 'submissions' => function ($q) {
            $q->latest()->limit(5);
        }])->get() ?? collect();

        return view('parent.dashboard', compact('students'));
    }

    public function studentProgress(Request $request, \App\Models\StudentProfile $student)
    {
        $parent = $request->user()->parentProfile;
        if (!$parent || !$parent->students()->where('student_id', $student->id)->exists()) {
            abort(403);
        }

        $progress = \App\Models\StudentProgress::where('student_id', $student->id)
            ->with(['surah', 'juz', 'circle'])
            ->latest()
            ->get();

        $submissions = $student->submissions()->latest()->limit(10)->get();
        $attendance = \App\Models\AttendanceRecord::where('student_id', $student->id)
            ->with('session')
            ->latest()
            ->limit(20)
            ->get();

        return view('parent.student-progress', compact('student', 'progress', 'submissions', 'attendance'));
    }
}
