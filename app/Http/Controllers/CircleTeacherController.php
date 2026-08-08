<?php

namespace App\Http\Controllers;

use App\Models\Circle;
use App\Models\CircleTeacher;
use App\Actions\Circles\AddTeacherToCircleAction;
use Illuminate\Http\Request;

class CircleTeacherController extends Controller
{
    // إضافة معلم للحلقة
    public function store(Request $request, Circle $circle, AddTeacherToCircleAction $action)
    {
        $data = $request->validate([
            'teacher_profile_ids'   => 'required|array',
            'teacher_profile_ids.*' => 'exists:teacher_profiles,id',
            'role'                  => 'required|in:primary,assistant',
            'status'                => 'required|in:active,paused',
        ]);

        $result = $action->execute($circle, $data['teacher_profile_ids'], $data['role'], $data['status']);

        if ($result['added'] === 0) {
            return back()->withErrors([
                'teacher_profile_ids' => 'كل الأساتذة المحددين مضافون للحلقة مسبقًا.',
            ]);
        }

        $message = "تم إضافة {$result['added']} أستاذ/ـة للحلقة بنجاح.";
        if ($result['skipped'] > 0) {
            $message .= " تم تجاهل {$result['skipped']} أستاذ/ـة لأنهم مضافون مسبقًا.";
        }

        return back()->with('success', $message);
    }

    // تعديل بيانات الربط (role/status)
    public function update(Request $request, CircleTeacher $circleTeacher)
    {
        $data = $request->validate([
            'role'   => 'required|in:primary,assistant',
            'status' => 'required|in:active,paused',
        ]);

        $circleTeacher->update($data);

        return back()->with('success', 'تم تحديث بيانات الأستاذ في الحلقة');
    }

    // حذف الربط
    public function destroy(CircleTeacher $circleTeacher)
    {
        $circleTeacher->delete();
        return back()->with('success', 'تم إزالة المعلم من الحلقة');
    }
}