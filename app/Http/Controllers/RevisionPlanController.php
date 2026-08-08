<?php

namespace App\Http\Controllers;

use App\Models\RevisionPlan;
use App\Models\RevisionPlanItem;
use App\Models\Circle;
use Illuminate\Http\Request;

class RevisionPlanController extends Controller
{
    public function index(Request $request)
    {
        $query = RevisionPlan::with(['student', 'circle']);

        $user = $request->user();
        if ($user->isTeacher() && $user->teacherProfile) {
            $query->where('teacher_id', $user->teacherProfile->id);
        } elseif ($user->isStudent() && $user->studentProfile) {
            $query->where('student_id', $user->studentProfile->id);
        }

        $plans = $query->latest()->paginate(20);
        return view('revision_plans.index', compact('plans'));
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
        return view('revision_plans.create', compact('circles'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'student_id' => 'required|exists:student_profiles,id',
            'circle_id' => 'required|exists:circles,id',
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'notes' => 'nullable|string|max:2000',
            'items' => 'required|array|min:1',
            'items.*.assignment_type' => 'required|in:new_memorization,close_revision,far_revision,consolidation',
            'items.*.surah_id' => 'required|exists:surahs,id',
            'items.*.juz_id' => 'required|exists:juz,id',
            'items.*.ayah_from' => 'required|integer|min:1',
            'items.*.ayah_to' => 'required|integer|gte:items.*.ayah_from',
            'items.*.scheduled_date' => 'nullable|date',
            'items.*.repetition_target' => 'nullable|integer|min:1|max:100',
        ]);

        $teacherId = null;
        if ($request->user()->isTeacher() && $request->user()->teacherProfile) {
            $teacherId = $request->user()->teacherProfile->id;
        }

        $plan = RevisionPlan::create([
            'student_id' => $data['student_id'],
            'teacher_id' => $teacherId ?? $request->teacher_id,
            'circle_id' => $data['circle_id'],
            'name' => $data['name'],
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
            'notes' => $data['notes'] ?? null,
            'created_by' => $request->user()->id,
        ]);

        foreach ($data['items'] as $item) {
            RevisionPlanItem::create([
                'revision_plan_id' => $plan->id,
                'assignment_type' => $item['assignment_type'],
                'surah_id' => $item['surah_id'],
                'juz_id' => $item['juz_id'],
                'ayah_from' => $item['ayah_from'],
                'ayah_to' => $item['ayah_to'],
                'scheduled_date' => $item['scheduled_date'] ?? null,
                'repetition_target' => $item['repetition_target'] ?? 1,
            ]);
        }

        return redirect()->route('revision-plans.show', $plan)
            ->with('success', 'تم إنشاء خطة المراجعة');
    }

    public function show(RevisionPlan $plan)
    {
        $plan->load(['student', 'circle', 'items.surah', 'items.juz']);
        return view('revision_plans.show', compact('plan'));
    }

    public function completeItem(Request $request, RevisionPlanItem $item)
    {
        $item->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        $allCompleted = $item->plan->items()->where('status', '!=', 'completed')->count() === 0;
        if ($allCompleted) {
            $item->plan->update(['status' => 'completed']);
        }

        return redirect()->back()->with('success', 'تم إكمال البند');
    }
}
