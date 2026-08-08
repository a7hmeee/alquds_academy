<?php

namespace App\Features\StudentSubmissions\Controllers;

use App\Actions\Recordings\CreateSubmissionAction;
use App\Actions\Recordings\TeacherReviewSubmissionAction;
use App\DTOs\Recordings\SubmissionData;
use App\Http\Controllers\Controller;
use App\Models\StudentSubmission;
use App\Models\Circle;
use App\Models\StudentProfile;
use App\Features\StudentSubmissions\Requests\StoreStudentSubmissionRequest;
use Illuminate\Http\Request;

class StudentSubmissionController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->hasRole('super admin')) {
            $submissions = StudentSubmission::with(['student.user','circle','surahModel','juzModel'])->latest()->get();
            return view('student_submissions.index', compact('submissions'));
        }

        if ($user->teacherProfile) {
            $teacherId = $user->teacherProfile->id;
            $circleIds = \App\Models\CircleTeacher::where('teacher_id', $teacherId)
                ->where('status', 'active')
                ->pluck('circle_id');
            $submissions = StudentSubmission::whereIn('circle_id', $circleIds)
                ->with(['student.user','circle','surahModel','juzModel'])
                ->latest()->get();
            return view('student_submissions.index', compact('submissions'));
        }

        $student = $user?->studentProfile;
        if (! $student) {
            return redirect()->route('student.dashboard')->with('error', 'حسابك ليس حساب طالب - لا توجد تسجيلات للعرض.');
        }

        $submissions = StudentSubmission::where('student_id', $student->id)
            ->with(['circle','surahModel','juzModel'])
            ->latest()->get();
        return view('student_submissions.index', compact('submissions'));
    }

    public function create(Circle $circle)
    {
        $user = auth()->user();
        $student = $user?->studentProfile;

        // allow super admin or teacher to open form for a student
        $availableStudents = null;
        if ($user->hasRole('super admin') || $user->teacherProfile) {
            $availableStudents = $circle->circleStudents()->with('student.user')->get()->map(fn($cs) => $cs->student);
        }

        if (! $student && ! $availableStudents) {
            return redirect()->route('student.dashboard')->with('error', 'غير مسموح: هذه الصفحة للطلاب فقط.');
        }

        return view('student_submissions.create', compact('circle','student','availableStudents'));
    }

    public function store(StoreStudentSubmissionRequest $request, Circle $circle, CreateSubmissionAction $action)
    {
        $user = $request->user();
        $student = $user?->studentProfile;

        // if current user is super admin or teacher, allow providing student_id
        if (! $student) {
            $studentId = $request->input('student_id');
            if (! $studentId) {
                return redirect()->back()->withInput()->with('error', 'اختر طالباً للرفع نيابةً عنه.');
            }
            $student = StudentProfile::find($studentId);
            if (! $student) {
                return redirect()->back()->withInput()->with('error', 'الطالب غير موجود.');
            }

            // ensure student belongs to the circle (unless super admin)
            if (! $user->hasRole('super admin')) {
                $belongs = $circle->circleStudents->pluck('student_id')->contains($student->id);
                if (! $belongs) {
                    return redirect()->back()->withInput()->with('error', 'الطالب غير مرتبط بهذه الحلقة.');
                }
            }
        }

        $validated = $request->validated();

        $data = SubmissionData::fromFeatureRequest(
            $validated,
            $student->id,
            $circle->id,
            $request->file('audio_file'),
            $request->file('image')
        );

        try {
            $action->execute($data);
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'حدث خطأ أثناء رفع الصوتية: ' . $e->getMessage());
        }

        // إذا كان المستخدم طالباً → وجّهه لصفحة التسجيلات الخاصة به
        if ($request->user()->studentProfile) {
            return redirect()->route('student.submissions')->with('success', 'تم رفع الصوتية بنجاح - بانتظار مراجعة المعلّم');
        }

        return redirect()->route('student-submissions.index')->with('success','تم رفع الصوتية بنجاح - بانتظار مراجعة المعلّم');
    }

    // teacher review screen (basic)
    public function review(StudentSubmission $studentSubmission)
    {
        $this->authorize('review', $studentSubmission);

        $user = auth()->user();
        if (! ($user?->teacherProfile || $user?->hasRole('super admin'))) {
            abort(403);
        }

        $submission = $studentSubmission;
        $submission->load(['surahModel','juzModel']);
        return view('student_submissions.review', compact('submission'));
    }

    public function updateReview(Request $request, StudentSubmission $studentSubmission, TeacherReviewSubmissionAction $action)
    {
        $this->authorize('review', $studentSubmission);

        $user = auth()->user();
        if (! ($user?->teacherProfile || $user?->hasRole('super admin') || $user?->hasRole('admin'))) {
            abort(403);
        }

        $submission = $studentSubmission;

        $reviewedBy = auth()->user()->teacherProfile?->id;

        $validated = $request->validate([
            'status' => 'required|in:reviewed,accepted,needs_work',
            'review_notes' => 'required|string|min:3',
            'rating' => 'nullable|integer|min:1|max:5',
            'score' => 'required|integer|min:0|max:100',
        ], [
            'review_notes.required' => 'ملاحظات المعلم إجبارية — يجب كتابة ملاحظات.',
            'score.required' => 'التقييم إجباري — يجب إدخال درجة من 0 إلى 100.',
            'score.min' => 'الدرجة يجب أن تكون 0 على الأقل.',
            'score.max' => 'الدرجة يجب أن تكون 100 كحد أقصى.',
        ]);

        $submission = $action->execute($submission, $validated, $reviewedBy);

        $submission->student->user->notify(new \App\Features\StudentSubmissions\Notifications\NewSubmissionNotification($submission, true));

        return redirect()->back()->with('success','تم حفظ تقييم المعلّم');
    }

    public function download(StudentSubmission $studentSubmission)
    {
        $this->authorize('view', $studentSubmission);

        $submission = $studentSubmission;
        $full = storage_path('app/public/' . $submission->file_path);
        return response()->download($full);
    }
}
