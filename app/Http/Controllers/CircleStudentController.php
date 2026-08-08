<?php

namespace App\Http\Controllers;

use App\Models\Circle;
use App\Models\CircleStudent;
use App\Models\StudentProfile;
use App\Actions\Circles\AddStudentToCircleAction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class CircleStudentController extends Controller
{
    // إضافة طالب للحلقة
    public function store(Request $request, Circle $circle, AddStudentToCircleAction $action)
    {
        $data = $request->validate([
            'student_id'         => 'required|exists:student_profiles,id',
            'status'             => 'required|in:active,paused',
            'joined_at'          => 'nullable|date',
        ]);

        try {
            $action->execute($circle, $data);
        } catch (\RuntimeException $e) {
            return back()->withErrors(['student_id' => $e->getMessage()]);
        }

        return back()->with('success', 'تم إضافة الطالب للحلقة');
    }

    // تحديث حالة الطالب داخل الحلقة **+ دعم ربط/فصل المعلم**
    public function update(Request $request, CircleStudent $circleStudent)
    {
        $data = $request->validate([
            'status'     => 'nullable|in:active,paused',
            'teacher_id' => 'nullable|exists:teacher_profiles,id',
        ]);

        // تحديث الحالة إذا وصلت
        if (isset($data['status'])) {
            $circleStudent->update(['status' => $data['status']]);
        }

        // ربط/فصل المعلم — فقط إذا العمود موجود في قاعدة البيانات
        if (array_key_exists('teacher_id', $data)) {
            if (Schema::hasColumn('student_profiles', 'teacher_id')) {
                $student = StudentProfile::find($circleStudent->student_id);
                if ($student) {
                    $student->update([ 'teacher_id' => $data['teacher_id'] ?? null ]);
                }
            } else {
                session()->flash('warning', 'عمود "teacher_id" غير موجود في جدول "student_profiles" — الربط لن يُحفظ.');
            }
        }

        return back()->with('success', 'تم تحديث بيانات الطالب في الحلقة');
    }

    // حذف طالب من الحلقة
    public function destroy(CircleStudent $circleStudent)
    {
        $circleStudent->delete();
        return back()->with('success', 'تم إزالة الطالب من الحلقة');
    }
}