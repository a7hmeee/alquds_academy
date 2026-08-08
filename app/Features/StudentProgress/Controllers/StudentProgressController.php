<?php

namespace App\Features\StudentProgress\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Circle;
use App\Models\StudentProfile;
use App\Models\TeacherProfile;
use App\Models\StudentProgress as StudentProgressModel;
use App\Features\StudentProgress\DTOs\StudentProgressData;
use App\Features\StudentProgress\Requests\StoreStudentProgressRequest;
use App\Features\StudentProgress\Requests\UpdateStudentProgressRequest;
use App\Features\StudentProgress\Services\StudentProgressService;
use App\Features\StudentProgress\Repositories\StudentProgressRepositoryInterface;
use App\Services\JuzProgressService;

class StudentProgressController extends Controller
{
    public function __construct(
        protected StudentProgressService $service,
        protected StudentProgressRepositoryInterface $repo
    ) {}

    public function index(Circle $circle)
    {
        $progresses = $this->repo->listByCircle($circle->id);

        // حساب التقدم التلقائي من التسجيلات لكل طالب
        $circle->load('circleStudents.student.user', 'juz');
        $autoProgress = collect();
        if ($circle->juz_id) {
            foreach ($circle->circleStudents as $cs) {
                if ($cs->student) {
                    $autoProgress[$cs->student->id] = JuzProgressService::calculate(
                        $cs->student->id, $circle->juz_id, $circle->id
                    );
                }
            }
        }

        return view('student_progress.index', compact('circle', 'progresses', 'autoProgress'));
    }

    public function create(Circle $circle)
    {
        $enrolledStudentIds = $circle->circleStudents()->pluck('student_id');
        $availableStudents = StudentProfile::with('user')
            ->whereIn('id', $enrolledStudentIds)
            ->orderBy('full_name')
            ->get();
        $availableTeachers = TeacherProfile::select('teacher_profiles.*')
            ->join('users','users.id','=','teacher_profiles.user_id')
            ->with('user')
            ->orderBy('users.email')
            ->get();

        return view('student_progress.create', compact('circle','availableStudents','availableTeachers'));
    }

    public function store(StoreStudentProgressRequest $request, Circle $circle)
    {
        $dto = StudentProgressData::fromRequest($request);
        $dto->circle_id = $circle->id;

        $progress = $this->service->create($dto);

        return redirect()
            ->route('circles.progress.index', $circle)
            ->with('success','تم إضافة سجل التقدّم');
    }

    public function edit(StudentProgressModel $studentProgress)
    {
        $studentProgress->load(['student.user','teacher.user','creator']);
        $availableTeachers = TeacherProfile::with('user')->orderBy('id','desc')->get();

        return view('student_progress.edit', compact('studentProgress','availableTeachers'));
    }

    public function update(UpdateStudentProgressRequest $request, StudentProgressModel $studentProgress)
    {
        $dto = StudentProgressData::fromRequest($request);
        $this->service->update($studentProgress, $dto);

        return back()->with('success','تم تحديث سجل التقدّم');
    }

    public function destroy(StudentProgressModel $studentProgress)
    {
        $this->service->delete($studentProgress);
        return back()->with('success','تم حذف سجل التقدّم');
    }

    // عرض التقدم من وجهة نظر الطالب
    public function studentView(Circle $circle)
    {
        $student = auth()->user()->studentProfile;

        if (!$student) {
            return redirect()->route('student.dashboard')->with('error', 'أنت لست طالباً');
        }

        // التحقق من أن الطالب في هذه الحلقة
        $circleStudent = $student->circles()
            ->where('circle_id', $circle->id)
            ->where('status', 'active')
            ->first();

        if (!$circleStudent) {
            return redirect()->route('student.dashboard')->with('error', 'أنت غير مرتبط بهذه الحلقة');
        }

        // احصل على سجلات التقدم الخاصة بالطالب في هذه الحلقة
        $progresses = StudentProgressModel::where('circle_id', $circle->id)
            ->where('student_id', $student->id)
            ->with('teacher.user', 'creator')
            ->orderBy('created_at', 'desc')
            ->get();

        // احصل على آخر تقدم (الحالية)
        $latestProgress = $progresses->first();

        return view('student_progress.student-view', compact('circle', 'student', 'progresses', 'latestProgress'));
    }
}
