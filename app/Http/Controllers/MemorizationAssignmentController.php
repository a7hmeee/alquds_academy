<?php

namespace App\Http\Controllers;

use App\Models\Circle;
use App\Models\MemorizationAssignment;
use App\Http\Requests\StoreMemorizationAssignmentRequest;
use App\Http\Requests\UpdateMemorizationAssignmentRequest;
use App\Actions\Memorization\CreateMemorizationAssignmentAction;
use App\Actions\Memorization\UpdateMemorizationAssignmentAction;
use App\Actions\Memorization\CompleteMemorizationAssignmentAction;
use App\Queries\Memorization\MemorizationAssignmentQuery;
use Illuminate\Http\Request;

class MemorizationAssignmentController extends Controller
{
    public function __construct(
        private MemorizationAssignmentQuery $query,
    ) {}

    public function index(Request $request)
    {
        $this->authorize('viewAny', MemorizationAssignment::class);

        $user = $request->user();
        $assignments = $this->query->forUser($user);

        return view('memorization_assignments.index', compact('assignments'));
    }

    public function create(Request $request)
    {
        $this->authorize('create', MemorizationAssignment::class);

        $user = $request->user();
        $circles = [];
        $students = [];

        if ($user->isSuperAdmin() || $user->isAdmin()) {
            $circles = Circle::where('status', 'active')->get();
        } elseif ($user->isTeacher() && $user->teacherProfile) {
            $circles = $user->teacherProfile->circles()->where('status', 'active')->get();
        }

        return view('memorization_assignments.create', compact('circles', 'students'));
    }

    public function store(StoreMemorizationAssignmentRequest $request, CreateMemorizationAssignmentAction $action)
    {
        $assignment = $action->execute($request->validated(), $request->user()->id);

        return redirect()->route('memorization-assignments.show', $assignment)
            ->with('success', 'تم إنشاء المهمة بنجاح');
    }

    public function show(MemorizationAssignment $assignment)
    {
        $this->authorize('view', $assignment);
        $assignment->load(['student', 'teacher', 'circle', 'surah', 'juz', 'submissions', 'creator']);

        return view('memorization_assignments.show', compact('assignment'));
    }

    public function edit(Request $request, MemorizationAssignment $assignment)
    {
        $this->authorize('update', $assignment);

        $user = $request->user();
        $circles = [];
        if ($user->isSuperAdmin() || $user->isAdmin()) {
            $circles = Circle::where('status', 'active')->get();
        } elseif ($user->isTeacher() && $user->teacherProfile) {
            $circles = $user->teacherProfile->circles()->where('status', 'active')->get();
        }

        return view('memorization_assignments.edit', compact('assignment', 'circles'));
    }

    public function update(UpdateMemorizationAssignmentRequest $request, MemorizationAssignment $assignment, UpdateMemorizationAssignmentAction $action)
    {
        $action->execute($assignment, $request->validated());

        return redirect()->route('memorization-assignments.show', $assignment)
            ->with('success', 'تم تحديث المهمة بنجاح');
    }

    public function destroy(MemorizationAssignment $assignment)
    {
        $this->authorize('delete', $assignment);
        $assignment->delete();

        return redirect()->route('memorization-assignments.index')
            ->with('success', 'تم حذف المهمة بنجاح');
    }

    public function complete(Request $request, MemorizationAssignment $assignment, CompleteMemorizationAssignmentAction $action)
    {
        $this->authorize('review', $assignment);

        $request->validate([
            'completion_percent' => 'nullable|integer|min:0|max:100',
        ]);

        $action->execute($assignment, $request->integer('completion_percent', 100));

        return redirect()->route('memorization-assignments.show', $assignment)
            ->with('success', 'تم إكمال المهمة بنجاح');
    }

    public function status(Request $request, MemorizationAssignment $assignment, UpdateMemorizationAssignmentAction $action)
    {
        $this->authorize('update', $assignment);

        $request->validate([
            'status' => 'required|in:in_progress,submitted,cancelled',
        ]);

        $action->execute($assignment, ['status' => $request->status]);

        return redirect()->route('memorization-assignments.show', $assignment)
            ->with('success', 'تم تحديث حالة المهمة');
    }

    public function getStudents(Request $request, Circle $circle)
    {
        $this->authorize('viewAny', MemorizationAssignment::class);

        $students = $circle->students()
            ->wherePivot('status', 'active')
            ->get(['student_profiles.id', 'student_profiles.full_name']);

        return response()->json($students);
    }
}
