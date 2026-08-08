<?php

namespace App\Http\Controllers;

use App\Models\Circle;
use App\Models\Organization;
use App\Models\StudentProfile;
use App\Http\Requests\StoreCircleRequest;
use App\Http\Requests\UpdateCircleRequest;
use App\Queries\Circles\CircleShowQuery;
use Illuminate\Http\Request;

class CircleController extends Controller
{
    // INDEX
    public function index()
    {
        $circles = Circle::latest()->paginate(10);
        return view('circles.index', compact('circles'));
    }

    // CREATE
    public function create()
    {
        $organizations = Organization::orderBy('name')->get();
        return view('circles.create', compact('organizations'));
    }

    // STORE
    public function store(StoreCircleRequest $request)
    {
        Circle::create($request->validated());

        return redirect()->route('circles.index')->with('success', 'تم إنشاء الحلقة بنجاح');
    }

    // SHOW
    public function show(Circle $circle, CircleShowQuery $query)
    {
        $data = $query->get($circle);

        return view('circles.show', $data);
    }

    // EDIT
    public function edit(Circle $circle)
    {
        $organizations = Organization::orderBy('name')->get();
        return view('circles.edit', compact('circle', 'organizations'));
    }

    // UPDATE
    public function update(UpdateCircleRequest $request, Circle $circle)
    {
        $circle->update($request->validated());

        return redirect()->route('circles.index')->with('success', 'تم تحديث الحلقة بنجاح');
    }

    // DESTROY (أرشفة)
    public function destroy(Circle $circle)
    {
        $circle->update(['status' => 'archived']);
        return redirect()->route('circles.index')->with('success', 'تمت أرشفة الحلقة');
    }

    // عرض تسجيلات طالب في حلقة معينة
    public function studentRecordings(Circle $circle, StudentProfile $student)
    {
        // التأكد أن الطالب ينتمي للحلقة
        $belongs = $circle->circleStudents()->where('student_id', $student->id)->exists();
        if (!$belongs) {
            abort(404, 'الطالب غير مسجل في هذه الحلقة');
        }

        $student->load('user');

        // جلب تسجيلات الطالب في هذه الحلقة
        $submissions = \App\Models\StudentSubmission::where('student_id', $student->id)
            ->where('circle_id', $circle->id)
            ->with(['surahModel','juzModel'])
            ->latest()
            ->get();

        // حساب تقدم الجزء بدقة
        $juzProgress = null;
        if ($circle->juz_id) {
            $juzProgress = \App\Services\JuzProgressService::calculate($student->id, $circle->juz_id, $circle->id);
        }

        return view('circles.student-recordings', compact('circle', 'student', 'submissions', 'juzProgress'));
    }
}