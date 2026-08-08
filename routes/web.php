<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Controllers
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserRoleController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\TeacherProfileController;
use App\Http\Controllers\StudentProfileController;
use App\Http\Controllers\StudentImportController;
use App\Http\Controllers\StudentDashboardController;
use App\Http\Controllers\CircleController;
use App\Http\Controllers\CircleTeacherController;
use App\Http\Controllers\CircleStudentController;

// StudentProgress feature controllers (feature-based)
use App\Features\StudentProgress\Controllers\StudentProgressController;
use App\Features\StudentProgress\Controllers\StudentProgressApiController;

// Quran Controller
use App\Http\Controllers\QuranController;
use App\Http\Controllers\Ajax\QuranAjaxController;

// Recording Controller
use App\Http\Controllers\RecordingController;

// Executive Dashboard
use App\Http\Controllers\ExecutiveDashboardController;

// Reports
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SystemReportController;
use App\Http\Controllers\AchievementController;
use App\Http\Controllers\CircleSessionController;
use App\Http\Controllers\MemorizationAssignmentController;
use App\Http\Controllers\MemorizationMistakeController;
use App\Http\Controllers\MemorizationSessionController;
use App\Http\Controllers\QuranExamController;
use App\Http\Controllers\RevisionPlanController;

/*
|--------------------------------------------------------------------------
| Public
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index']);

/*
|--------------------------------------------------------------------------
| ADMIN PANEL — super admin, admin, teacher only
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified', 'role:super admin,admin,teacher'])->prefix('admin')->group(function () {

    // Dashboard
    Route::get('/dashboard', [ExecutiveDashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/dashboard/refresh', [ExecutiveDashboardController::class, 'refreshCache'])->name('admin.dashboard.refresh');

    // Roles
    Route::resource('roles', RoleController::class)
        ->only(['index','create','store','edit','update']);

    // Users & Roles
    Route::get('/users', [UserRoleController::class,'index'])->name('users.index');
    Route::post('/users/{user}/change-role',
        [UserRoleController::class,'change']
    )->name('users.changeRole');

    // Organizations
    Route::resource('organizations', OrganizationController::class)
        ->only(['index','create','store','edit','update','destroy']);

    // Teachers
    Route::resource('teachers', TeacherProfileController::class);

    // Students
    Route::resource('students', StudentProfileController::class);
    Route::get('/students-import', [StudentImportController::class, 'show'])->name('students.import.show');
    Route::post('/students-import', [StudentImportController::class, 'import'])->name('students.import');
    Route::get('/students-import/template', [StudentImportController::class, 'downloadTemplate'])->name('students.import.template');

    // Circles CRUD
    Route::resource('circles', CircleController::class);

    // Teachers in Circle
    Route::post('/circles/{circle}/teachers', [CircleTeacherController::class,'store'])->name('circles.teachers.store');
    Route::put('/circle-teachers/{circleTeacher}', [CircleTeacherController::class,'update'])->name('circle-teachers.update');
    Route::delete('/circle-teachers/{circleTeacher}', [CircleTeacherController::class,'destroy'])->name('circle-teachers.destroy');

    // Students in Circle
    Route::post('/circles/{circle}/students', [CircleStudentController::class,'store'])->name('circles.students.store');
    Route::put('/circle-students/{circleStudent}', [CircleStudentController::class,'update'])->name('circle-students.update');
    Route::delete('/circle-students/{circleStudent}', [CircleStudentController::class,'destroy'])->name('circle-students.destroy');

    // Student Recordings in Circle
    Route::get('/circles/{circle}/students/{student}/recordings', [CircleController::class,'studentRecordings'])->name('circles.students.recordings');

    // Student Progress (المحتوى اليومي)
    Route::get('/circles/{circle}/progress', [StudentProgressController::class,'index'])->name('circles.progress.index');
    Route::get('/circles/{circle}/progress/create', [StudentProgressController::class,'create'])->name('circles.progress.create');
    Route::post('/circles/{circle}/progress', [StudentProgressController::class,'store'])->name('circles.progress.store');
    Route::get('/student-progress/{studentProgress}/edit', [StudentProgressController::class,'edit'])->name('student-progress.edit');
    Route::put('/student-progress/{studentProgress}', [StudentProgressController::class,'update'])->name('student-progress.update');
    Route::delete('/student-progress/{studentProgress}', [StudentProgressController::class,'destroy'])->name('student-progress.destroy');

    // Submissions Review (Admin/Teacher)
    Route::get('/submissions', [\App\Features\StudentSubmissions\Controllers\StudentSubmissionController::class,'index'])->name('student-submissions.index');
    Route::get('/submissions/{studentSubmission}/review', [\App\Features\StudentSubmissions\Controllers\StudentSubmissionController::class,'review'])->name('submissions.review');
    Route::put('/submissions/{studentSubmission}/review', [\App\Features\StudentSubmissions\Controllers\StudentSubmissionController::class,'updateReview'])->name('submissions.review.update');

    // Submission download (shared but accessible from admin panel)
    Route::get('/submissions/{studentSubmission}/download', [\App\Features\StudentSubmissions\Controllers\StudentSubmissionController::class,'download'])->name('admin.submissions.download');

    // Reports
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/student/{student}', [ReportController::class, 'studentReport'])->name('reports.student');
    Route::get('/reports/teacher/{teacher}', [ReportController::class, 'teacherReport'])->name('reports.teacher');
    Route::get('/reports/circle/{circle}', [ReportController::class, 'circleReport'])->name('reports.circle');
    Route::get('/reports/organization/{organization}', [ReportController::class, 'organizationReport'])->name('reports.organization');
    Route::get('/reports/system', [SystemReportController::class, 'index'])->name('reports.system');

    // API (basic)
    Route::get('/api/circles/{circle}/progress', [StudentProgressApiController::class,'index'])->name('api.circles.progress.index');
    Route::post('/api/circles/{circle}/progress', [StudentProgressApiController::class,'store'])->name('api.circles.progress.store');
    Route::get('/api/circles/{circle}/submissions', [\App\Features\StudentSubmissions\Controllers\StudentSubmissionApiController::class,'index'])->name('api.circles.submissions.index');
    Route::post('/api/circles/{circle}/submissions', [\App\Features\StudentSubmissions\Controllers\StudentSubmissionApiController::class,'store'])->name('api.circles.submissions.store');
    Route::get('/api/circles/{circle}/submissions/statistics', [\App\Features\StudentSubmissions\Controllers\StudentSubmissionApiController::class,'statistics'])->name('api.circles.submissions.statistics');

    // Memorization Assignments
    Route::resource('memorization-assignments', MemorizationAssignmentController::class);
    Route::put('/memorization-assignments/{memorization_assignment}/complete', [MemorizationAssignmentController::class, 'complete'])->name('memorization-assignments.complete');
    Route::patch('/memorization-assignments/{memorization_assignment}/status', [MemorizationAssignmentController::class, 'status'])->name('memorization-assignments.status');
    Route::get('/memorization-assignments/circles/{circle}/students', [MemorizationAssignmentController::class, 'getStudents'])->name('memorization-assignments.circle-students');

    // Memorization Sessions
    Route::resource('memorization-sessions', MemorizationSessionController::class);

    // Memorization Mistakes
    Route::post('/memorization-sessions/{session}/mistakes', [MemorizationMistakeController::class, 'store'])->name('memorization-sessions.mistakes.store');
    Route::patch('/memorization-mistakes/{mistake}/resolve', [MemorizationMistakeController::class, 'resolve'])->name('memorization-mistakes.resolve');

    // Revision Plans
    Route::resource('revision-plans', RevisionPlanController::class);
    Route::patch('/revision-plan-items/{item}/complete', [RevisionPlanController::class, 'completeItem'])->name('revision-plans.items.complete');

    // Circle Sessions & Attendance
    Route::get('/circles/{circle}/sessions', [CircleSessionController::class, 'index'])->name('circle-sessions.index');
    Route::get('/circles/{circle}/sessions/create', [CircleSessionController::class, 'create'])->name('circle-sessions.create');
    Route::post('/circles/{circle}/sessions', [CircleSessionController::class, 'store'])->name('circle-sessions.store');
    Route::get('/circles/{circle}/sessions/{session}', [CircleSessionController::class, 'show'])->name('circle-sessions.show');
    Route::get('/circles/{circle}/sessions/{session}/attendance', [CircleSessionController::class, 'attendance'])->name('circle-sessions.attendance');
    Route::post('/circles/{circle}/sessions/{session}/attendance', [CircleSessionController::class, 'saveAttendance'])->name('circle-sessions.attendance.save');

    // Quran Exams
    Route::resource('quran-exams', QuranExamController::class);
    Route::post('/quran-exams/{quran_exam}/results', [QuranExamController::class, 'saveResult'])->name('quran-exams.results.store');

    // Achievements & Certificates
    Route::get('/achievements', [AchievementController::class, 'index'])->name('achievements.index');
    Route::get('/certificates', [AchievementController::class, 'certificates'])->name('certificates.index');
});

/*
|--------------------------------------------------------------------------
| STUDENT PANEL — student only
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified', 'role:student,super admin'])->prefix('student')->group(function () {

    // Dashboard
    Route::get('/dashboard', [StudentDashboardController::class, 'dashboard'])->name('student.dashboard');
    Route::get('/submissions', [StudentDashboardController::class, 'submissions'])->name('student.submissions');
    Route::get('/circles', [StudentDashboardController::class, 'circles'])->name('student.circles');
    Route::get('/progress', [StudentDashboardController::class, 'progress'])->name('student.progress');
    Route::get('/recordings-list', [StudentDashboardController::class, 'recordingsList'])->name('student.recordings.list');

    // Join Circle
    Route::post('/circles/{circle}/join', [StudentDashboardController::class, 'joinCircle'])->name('student.join-circle');

    // Student circles - my progress (view only)
    Route::get('/circles/{circle}/my-progress', [StudentProgressController::class,'studentView'])->name('circles.my-progress');

    // Recordings
    Route::get('/recordings', [RecordingController::class, 'dashboard'])->name('recordings.dashboard');
    Route::get('/recordings/upload', [RecordingController::class, 'uploadPage'])->name('recordings.upload');
    Route::post('/recordings/store', [RecordingController::class, 'store'])->name('recordings.store');
    Route::get('/recordings/{submission}', [RecordingController::class, 'show'])->name('recordings.show');
    Route::post('/recordings/{submission}/rate', [RecordingController::class, 'rate'])->name('recordings.rate');
    Route::delete('/recordings/{submission}', [RecordingController::class, 'delete'])->name('recordings.delete');

    // Bulk Recording Import
    Route::get('/recordings/bulk-import', [RecordingController::class, 'bulkImportPage'])->name('recordings.bulkImport.page');
    Route::post('/recordings/bulk-import', [RecordingController::class, 'bulkImport'])->name('recordings.bulkImport');
    Route::get('/recordings/bulk-import/template', [RecordingController::class, 'downloadBulkTemplate'])->name('recordings.bulkImport.template');

    // Recording API
    Route::get('/api/recordings/surahs', [RecordingController::class, 'apiSurahs'])->name('api.recordings.surahs');
    Route::get('/api/recordings/surahs/search', [RecordingController::class, 'apiSearchSurahs'])->name('api.recordings.surahs.search');
    Route::get('/api/recordings/surah/{surahId}/juz', [RecordingController::class, 'apiSurahJuz'])->name('api.recordings.surah.juz');
    Route::get('/api/recordings/surah/{surahId}/juz/{juzId}/ayahs', [RecordingController::class, 'apiSurahJuzAyahs'])->name('api.recordings.surah.juz.ayahs');

    // Student submissions (create)
    Route::get('/circles/{circle}/submissions/create', [\App\Features\StudentSubmissions\Controllers\StudentSubmissionController::class,'create'])->name('circles.submissions.create');
    Route::post('/circles/{circle}/submissions', [\App\Features\StudentSubmissions\Controllers\StudentSubmissionController::class,'store'])->name('circles.submissions.store');
    Route::get('/submissions/{studentSubmission}/download', [\App\Features\StudentSubmissions\Controllers\StudentSubmissionController::class,'download'])->name('submissions.download');

    // Student submissions API
    Route::get('/api/student/submissions/statistics', [\App\Features\StudentSubmissions\Controllers\StudentSubmissionApiController::class,'studentStats'])->name('api.student.submissions.statistics');

    // Student assignments
    Route::get('/assignments', [\App\Http\Controllers\StudentDashboardController::class, 'assignments'])->name('student.assignments');
    Route::get('/assignments/{assignment}', [\App\Http\Controllers\StudentDashboardController::class, 'showAssignment'])->name('student.assignments.show');
    Route::patch('/assignments/{assignment}/status', [\App\Http\Controllers\StudentDashboardController::class, 'updateAssignmentStatus'])->name('student.assignments.status');
});

/*
|--------------------------------------------------------------------------
| PARENT PANEL — parent only
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified', 'role:parent,super admin'])->prefix('parent')->name('parent.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\ParentDashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/students/{student}/progress', [\App\Http\Controllers\ParentDashboardController::class, 'studentProgress'])->name('student.progress');
});

/*
|--------------------------------------------------------------------------
| SHARED — accessible to all authenticated users
|--------------------------------------------------------------------------
*/

// Profile
Route::middleware('auth')->group(function () {
    Route::get('/profile',  [ProfileController::class,'edit'])->name('profile.edit');
    Route::patch('/profile',[ProfileController::class,'update'])->name('profile.update');
    Route::delete('/profile',[ProfileController::class,'destroy'])->name('profile.destroy');
});

// Quran
Route::middleware('auth')->group(function () {
    Route::get('/quran', [QuranController::class, 'index'])->name('quran.index');
    Route::get('/quran/surah/{surah}', [QuranController::class, 'showSurah'])->name('quran.surah.show');
    Route::get('/quran/surah/{surahId}/ayah/{ayahNumber}', [QuranController::class, 'showAyah'])->name('quran.ayah.show');
    Route::get('/quran/juz', [QuranController::class, 'indexJuz'])->name('quran.juz.index');
    Route::get('/quran/juz/{juz}', [QuranController::class, 'showJuz'])->name('quran.juz.show');
    Route::get('/quran/search', [QuranController::class, 'search'])->name('quran.search');
    Route::get('/quran/statistics', [QuranController::class, 'statistics'])->name('quran.statistics');

    // Quran Ajax (web-based JSON)
    Route::get('/api/quran/surahs', [QuranAjaxController::class, 'surahs'])->name('api.quran.surahs');
    Route::get('/api/quran/surahs/search', [QuranAjaxController::class, 'searchSurahs'])->name('api.quran.surahs.search');
    Route::get('/api/quran/surah/{surah}/ayahs', [QuranAjaxController::class, 'surahAyahs'])->name('api.quran.surah.ayahs');
    Route::get('/api/quran/surah/{surah}/juz', [QuranAjaxController::class, 'surahJuz'])->name('api.quran.surah.juz');
    Route::get('/api/quran/surah/{surah}/juz/{juz}/ayahs', [QuranAjaxController::class, 'surahJuzAyahs'])->name('api.quran.surah.juz.ayahs');
    Route::get('/api/quran/juz', [QuranAjaxController::class, 'juzList'])->name('api.quran.juz');
    Route::get('/api/quran/juz/{juz}/ayahs', [QuranAjaxController::class, 'juzAyahs'])->name('api.quran.juz.ayahs');
    Route::get('/api/quran/statistics', [QuranAjaxController::class, 'statistics'])->name('api.quran.statistics');
});

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/
Route::prefix('api')->group(function () {
    require __DIR__.'/api.php';
});

/*
|--------------------------------------------------------------------------
| Auth
|--------------------------------------------------------------------------
*/
require __DIR__.'/auth.php';
