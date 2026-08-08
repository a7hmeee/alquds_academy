<?php

namespace App\Http\Controllers;

use App\Services\StudentImportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StudentImportController extends Controller
{
    protected $importService;

    public function __construct(StudentImportService $importService)
    {
        $this->importService = $importService;
    }

    /**
     * عرض صفحة الاستيراد مع نموذج الاستيراد
     */
    public function show()
    {
        return view('students.import');
    }

    /**
     * معالجة الاستيراد من ملف
     */
    public function import(Request $request)
    {
        $validated = $request->validate([
            'import_file' => 'required|file|mimes:xlsx,xls,csv|max:5120', // 5MB
        ], [
            'import_file.required' => 'يجب اختيار ملف للاستيراد',
            'import_file.file' => 'يجب رفع ملف صحيح',
            'import_file.mimes' => 'يجب أن يكون الملف بصيغة Excel أو CSV',
            'import_file.max' => 'حجم الملف لا يجب أن يزيد عن 5MB',
        ]);

        try {
            // حفظ الملف مؤقتاً
            $filePath = $request->file('import_file')->store('imports', 'local');
            $fullPath = storage_path('app/' . $filePath);

            // استيراد البيانات
            $result = $this->importService->import($fullPath);

            // حذف الملف المؤقت
            if (file_exists($fullPath)) {
                unlink($fullPath);
            }

            // إعادة النتيجة
            return response()->json([
                'success' => true,
                'message' => "تم إضافة {$result['success']} طالب بنجاح" . 
                    ($result['failed'] > 0 ? " و فشل إضافة {$result['failed']} طالب" : ''),
                'data' => $result,
            ]);

        } catch (\Exception $e) {
            // حذف الملف في حالة الخطأ
            if (isset($fullPath) && file_exists($fullPath)) {
                unlink($fullPath);
            }

            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ: ' . $e->getMessage(),
            ], 422);
        }
    }

    /**
     * تحميل نموذج Excel
     */
    public function downloadTemplate()
    {
        $filePath = storage_path('app/templates/student_import_template.xlsx');

        if (!file_exists($filePath)) {
            return response()->json([
                'success' => false,
                'message' => 'ملف النموذج غير موجود',
            ], 404);
        }

        return response()->download($filePath, 'نموذج_استيراد_الطلاب.xlsx');
    }
}
