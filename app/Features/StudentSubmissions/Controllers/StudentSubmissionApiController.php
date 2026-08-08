<?php

namespace App\Features\StudentSubmissions\Controllers;

use App\Actions\Recordings\CreateSubmissionAction;
use App\DTOs\Recordings\SubmissionData;
use App\Http\Controllers\Controller;
use App\Models\Circle;
use App\Models\StudentSubmission;
use Illuminate\Http\Request;

class StudentSubmissionApiController extends Controller
{
    public function index(Circle $circle)
    {
        $subs = StudentSubmission::where('circle_id', $circle->id)->with('student.user','reviewer.user')->latest()->get();
        return response()->json(['data' => $subs]);
    }

    public function store(Request $request, Circle $circle, CreateSubmissionAction $action)
    {
        $student = $request->user()->studentProfile;

        if (!$student) {
            return response()->json(['error' => 'غير مسموح: حساب الطالب غير موجود'], 403);
        }

        $validated = $request->validate([
            'audio' => 'required|file|mimes:mp3,wav,m4a,ogg|max:10240',
            'image' => 'nullable|image|max:2048',
            'surah' => 'nullable|string|max:191',
            'ayah' => 'nullable|integer|min:1',
            'juz' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
        ]);

        $data = SubmissionData::fromFeatureRequest(
            $validated,
            $student->id,
            $circle->id,
            $request->file('audio'),
            $request->file('image'),
            'submissions/audio',
            'submissions/images'
        );

        try {
            $submission = $action->execute($data);

            return response()->json(['data' => $submission], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'حدث خطأ: ' . $e->getMessage()], 500);
        }
    }

    public function statistics(Circle $circle)
    {
        $total = StudentSubmission::where('circle_id', $circle->id)->count();
        $pending = StudentSubmission::where('circle_id', $circle->id)->where('status', 'pending')->count();
        $accepted = StudentSubmission::where('circle_id', $circle->id)->where('status', 'accepted')->count();
        $needsWork = StudentSubmission::where('circle_id', $circle->id)->where('status', 'needs_work')->count();

        return response()->json([
            'total' => $total,
            'pending' => $pending,
            'accepted' => $accepted,
            'needs_work' => $needsWork,
        ]);
    }

    public function studentStats()
    {
        $student = auth()->user()->studentProfile;
        
        if (!$student) {
            return response()->json(['error' => 'غير مسموح'], 403);
        }

        $total = StudentSubmission::where('student_id', $student->id)->count();
        $pending = StudentSubmission::where('student_id', $student->id)->where('status', 'pending')->count();
        $accepted = StudentSubmission::where('student_id', $student->id)->where('status', 'accepted')->count();
        $needsWork = StudentSubmission::where('student_id', $student->id)->where('status', 'needs_work')->count();
        $avgRating = StudentSubmission::where('student_id', $student->id)->whereNotNull('rating')->avg('rating');

        return response()->json([
            'total' => $total,
            'pending' => $pending,
            'accepted' => $accepted,
            'needs_work' => $needsWork,
            'avg_rating' => round($avgRating, 1),
        ]);
    }
}
