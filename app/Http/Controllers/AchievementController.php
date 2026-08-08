<?php

namespace App\Http\Controllers;

use App\Models\StudentAchievement;
use App\Models\Certificate;
use Illuminate\Http\Request;

class AchievementController extends Controller
{
    public function index(Request $request)
    {
        $query = StudentAchievement::with(['student', 'surah', 'juz']);

        $user = $request->user();
        if ($user->isStudent() && $user->studentProfile) {
            $query->where('student_id', $user->studentProfile->id);
        }

        $achievements = $query->latest()->paginate(20);
        return view('achievements.index', compact('achievements'));
    }

    public function certificates(Request $request)
    {
        $query = Certificate::with(['student', 'issuer']);

        $user = $request->user();
        if ($user->isStudent() && $user->studentProfile) {
            $query->where('student_id', $user->studentProfile->id);
        }

        $certificates = $query->latest()->paginate(20);
        return view('achievements.certificates', compact('certificates'));
    }
}
