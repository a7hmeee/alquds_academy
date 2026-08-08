<?php

namespace App\Http\Controllers;

use App\Actions\Recordings\CreateSubmissionAction;
use App\Actions\Recordings\ReviewSubmissionAction;
use App\DTOs\Recordings\SubmissionData;
use App\Http\Requests\Recordings\ReviewSubmissionRequest;
use App\Http\Requests\Recordings\StoreSubmissionRequest;
use App\Models\StudentSubmission;
use App\Models\Surah;
use App\Models\Juz;
use App\Models\StudentProfile;
use App\Queries\Recordings\SurahJuzQuery;
use App\Services\FileUploadService;
use App\Services\RecordingBulkImportService;
use Illuminate\Http\Request;

class RecordingController extends Controller
{
    /**
     * Main Recording Dashboard
     */
    public function dashboard()
    {
        $student = auth()->user()->studentProfile;
        
        if (!$student) {
            return redirect()->route('student.dashboard')->with('error', 'ليست طالباً');
        }

        $submissions = $student->submissions()->latest()->get();
        $circle = $student->circle;
        
        // إحصائيات
        $stats = [
            'total' => $submissions->count(),
            'pending' => $submissions->where('status', 'pending')->count(),
            'accepted' => $submissions->where('status', 'accepted')->count(),
            'needs_work' => $submissions->where('status', 'needs_work')->count(),
            'avg_rating' => $submissions->whereNotNull('rating')->avg('rating'),
        ];

        return view('recordings.dashboard', compact('student', 'submissions', 'stats', 'circle'));
    }

    /**
     * Upload Page with Dynamic Selection
     */
    public function uploadPage()
    {
        $student = auth()->user()->studentProfile;
        
        if (!$student) {
            return redirect()->route('student.dashboard')->with('error', 'ليست طالباً');
        }

        $circle = $student->circle;
        
        if (!$circle) {
            return redirect()->route('student.dashboard')->with('error', 'لم تُسجل في أي حلقة');
        }

        // كل السور للإظهار الأولي
        $surahs = Surah::all()->map(fn($s) => [
            'id' => $s->id,
            'name_ar' => $s->name_ar,
            'name_en' => $s->name_en,
            'number' => $s->id,
        ])->toArray();

        return view('recordings.upload', compact('student', 'circle', 'surahs'));
    }

    /**
     * API: Get Surahs with Juz info
     */
    public function apiSurahs()
    {
        return response()->json(SurahJuzQuery::allSurahsWithCounts());
    }

    /**
     * API: Search Surahs
     */
    public function apiSearchSurahs(Request $request)
    {
        $query = $request->query('q', '');

        return response()->json(SurahJuzQuery::searchSurahs($query));
    }

    /**
     * API: Get Juz for Surah
     */
    public function apiSurahJuz($surahId)
    {
        try {
            $juzzes = SurahJuzQuery::juzForSurah((int) $surahId);

            if ($juzzes->isEmpty()) {
                return response()->json([
                    'error' => 'لا توجد بيانات أجزاء لهذه السورة',
                    'surah_id' => (int) $surahId,
                ], 400);
            }

            return response()->json($juzzes);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'خطأ في جلب الأجزاء: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * API: Get Ayahs for Surah + Juz
     */
    public function apiSurahJuzAyahs(Request $request, $surahId, $juzId)
    {
        try {
            $result = SurahJuzQuery::ayahsForSurahJuz((int) $surahId, (int) $juzId);

            return response()->json([
                'ayahs' => $result['ayahs']->toArray(),
                'count' => $result['count'],
                'from' => $result['from'],
                'to' => $result['to'],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'خطأ في جلب الآيات: ' . $e->getMessage(),
                'surah_id' => $surahId,
                'juz_id' => $juzId,
            ], 500);
        }
    }

    /**
     * Store Recording
     */
    public function store(StoreSubmissionRequest $request, CreateSubmissionAction $action)
    {
        $student = auth()->user()->studentProfile;

        if (!$student) {
            return response()->json(['error' => 'غير مسموح'], 403);
        }

        $validated = $request->validated();

        $surah = Surah::find($validated['surah_id']);
        $juz = Juz::find($validated['juz_id']);

        $data = SubmissionData::fromStoreSubmissionRequest(
            $validated,
            $student->id,
            $surah,
            $juz,
            $request->file('audio'),
            $request->file('image')
        );

        try {
            $submission = $action->execute($data);

            return response()->json([
                'success' => true,
                'message' => 'تم رفع التسجيل بنجاح',
                'submission' => $submission,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'حدث خطأ: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Rate Recording
     */
    public function rate(ReviewSubmissionRequest $request, StudentSubmission $submission, ReviewSubmissionAction $action)
    {
        $this->authorize('view', $submission);

        $user = auth()->user();
        $student = $user->studentProfile;

        $isOwner = $student && $submission->student_id === $student->id;
        $isTeacher = (bool) $user->teacherProfile;
        $isSuperAdmin = $user->hasRole('super admin') || $user->hasRole('admin');

        if (! ($isOwner || $isTeacher || $isSuperAdmin)) {
            return response()->json(['error' => 'غير مسموح'], 403);
        }

        $validated = $request->validated();

        $action->execute($submission, $validated['self_rating'] ?? null, $validated['notes'] ?? null);

        return response()->json([
            'success' => true,
            'message' => 'تم حفظ التقييم',
        ]);
    }

    /**
     * Delete Recording
     */
    public function delete(StudentSubmission $submission)
    {
        $this->authorize('delete', $submission);

        FileUploadService::deleteFile($submission->file_path);
        FileUploadService::deleteFile($submission->image_path);

        $submission->delete();

        return response()->json(['success' => true, 'message' => 'تم حذف التسجيل']);
    }

    /**
     * Bulk Import Page
     */
    public function bulkImportPage()
    {
        $student = auth()->user()->studentProfile;
        
        if (!$student) {
            return redirect()->route('student.dashboard')->with('error', 'ليست طالباً');
        }

        return view('recordings.bulk-import', compact('student'));
    }

    /**
     * Bulk Import Process
     */
    public function bulkImport(Request $request)
    {
        $student = auth()->user()->studentProfile;
        
        if (!$student) {
            return response()->json(['error' => 'غير مسموح'], 403);
        }

        $validated = $request->validate([
            'file' => 'required|file|mimes:csv,xlsx,xls|max:5120',
        ]);

        try {
            $filePath = $request->file('file')->store('bulk-imports', 'public');

            $service = new RecordingBulkImportService();
            $result = $service->import($filePath, $student->id);

            FileUploadService::deleteFile($filePath);

            return response()->json($result);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'حدث خطأ: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Download Bulk Import Template
     */
    public function downloadBulkTemplate()
    {
        $content = RecordingBulkImportService::getTemplate();

        return response($content, 200)
            ->header('Content-Type', 'text/csv; charset=utf-8')
            ->header('Content-Disposition', 'attachment; filename="recording_template.csv"')
            ->header('Content-Length', strlen($content));
    }

    /**
     * Get Recording Details
     */
    public function show(StudentSubmission $submission)
    {
        $this->authorize('view', $submission);

        $student = auth()->user()->studentProfile;

        return view('recordings.show', compact('submission', 'student'));
    }
}
