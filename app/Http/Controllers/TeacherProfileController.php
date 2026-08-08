<?php

namespace App\Http\Controllers;

use App\Models\TeacherProfile;
use App\Models\User;
use App\Actions\Teachers\CreateTeacherAction;
use App\Actions\Teachers\UpdateTeacherAction;
use App\Http\Requests\StoreTeacherRequest;
use App\Http\Requests\UpdateTeacherRequest;
use Illuminate\Http\Request;

class TeacherProfileController extends Controller
{
    /**
     * عرض جميع المعلمين
     */
    public function index()
    {
        $teachers = TeacherProfile::with('user')->latest()->get();
        return view('teachers.index', compact('teachers'));
    }

    /**
     * نموذج إنشاء معلم
     */
    public function create()
    {
        // فقط المستخدمين اللي دورهم teacher
        $users = User::role('teacher')->doesntHave('teacherProfile')->get();
        return view('teachers.create', compact('users'));
    }

    /**
     * تخزين معلم جديد
     */
   public function store(StoreTeacherRequest $request, CreateTeacherAction $action)
{
    $action->execute($request->validated(), $request->file('photo'));

    return redirect()->route('teachers.index')
        ->with('success', 'تم إنشاء المعلم بنجاح');
}
/**
 * عرض تفاصيل المعلم كاملة
 */
public function show(TeacherProfile $teacher)
{
    $teacher->load(['user', 'students.user', 'circles']);

    return view('teachers.show', compact('teacher'));
}
    /**
     * نموذج تعديل المعلم
     */
    public function edit(TeacherProfile $teacher)
    {
        return view('teachers.edit', compact('teacher'));
    }

    /**
     * تحديث بيانات المعلم
     */
    public function update(UpdateTeacherRequest $request, TeacherProfile $teacher, UpdateTeacherAction $action)
    {
        $action->execute($teacher, $request->validated(), $request->file('photo'));

        return redirect()->route('teachers.index')
            ->with('success', 'تم تحديث بيانات المعلم');
    }

    /**
     * حذف معلم
     */
    public function destroy(TeacherProfile $teacher)
    {
        \App\Services\FileUploadService::deleteFile($teacher->photo);

        $teacher->delete();

        return redirect()->route('teachers.index')
            ->with('success', 'تم حذف المعلم');
    }
}