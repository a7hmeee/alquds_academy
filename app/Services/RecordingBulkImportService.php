<?php

namespace App\Services;

use App\Models\StudentSubmission;
use App\Models\Surah;
use App\Models\Juz;
use App\Models\StudentProfile;
use Illuminate\Support\Facades\Storage;
use Exception;

class RecordingBulkImportService
{
    private $errors = [];
    private $successCount = 0;
    private $totalRows = 0;

    /**
     * Import recordings from CSV/Excel
     */
    public function import($filePath, $studentId)
    {
        $this->errors = [];
        $this->successCount = 0;
        $this->totalRows = 0;

        try {
            $student = StudentProfile::findOrFail($studentId);
            
            if (!$student->circle_id) {
                throw new Exception('الطالب غير مسجل في أي حلقة');
            }

            // قراءة الملف
            $ext = pathinfo($filePath, PATHINFO_EXTENSION);
            $data = $this->parseFile($filePath, $ext);

            if (empty($data)) {
                throw new Exception('الملف فارغ أو لا يحتوي على بيانات');
            }

            // معالجة الصفوف
            foreach ($data as $rowIndex => $row) {
                $this->totalRows++;
                
                try {
                    $this->processRow($row, $student, $rowIndex + 2); // رقم السطر + 1 للرأس
                } catch (Exception $e) {
                    $this->errors[] = [
                        'row' => $rowIndex + 2,
                        'message' => $e->getMessage(),
                    ];
                }
            }

            return [
                'success' => true,
                'total' => $this->totalRows,
                'imported' => $this->successCount,
                'failed' => $this->totalRows - $this->successCount,
                'errors' => $this->errors,
            ];

        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Parse CSV or Excel file
     */
    private function parseFile($filePath, $ext)
    {
        if ($ext === 'csv') {
            return $this->parseCSV($filePath);
        } elseif (in_array($ext, ['xlsx', 'xls'])) {
            return $this->parseExcel($filePath);
        } else {
            throw new Exception('صيغة الملف غير معروفة');
        }
    }

    /**
     * Parse CSV file
     */
    private function parseCSV($filePath)
    {
        $data = [];
        $file = fopen(storage_path('app/public') . '/' . $filePath, 'r');
        
        if (!$file) {
            throw new Exception('لا يمكن فتح الملف');
        }

        // تخطي الرأس
        fgets($file);

        while (($row = fgetcsv($file, 1000, ',')) !== false) {
            if (!empty(array_filter($row))) {
                $data[] = $row;
            }
        }

        fclose($file);
        return $data;
    }

    /**
     * Parse Excel file
     */
    private function parseExcel($filePath)
    {
        // للآن، نستخدم CSV فقط
        // يمكن لاحقاً إضافة PhpSpreadsheet
        throw new Exception('يرجى استخدام ملف CSV');
    }

    /**
     * Process single row
     */
    private function processRow($row, $student, $rowNumber)
    {
        // التحقق من أن الصف يحتوي على البيانات المطلوبة
        if (count($row) < 1 || empty(trim($row[0] ?? ''))) {
            throw new Exception('بيانات ناقصة في الصف');
        }

        // استخراج البيانات
        $surahName = trim($row[0] ?? '');
        $juzNumber = trim($row[1] ?? '');
        $ayahFrom = intval($row[2] ?? 0);
        $ayahTo = intval($row[3] ?? 0) ?: null;
        $notes = trim($row[4] ?? '');
        $filePath = trim($row[5] ?? '');

        // validation
        if (!$surahName) {
            throw new Exception('اسم السورة مفقود');
        }

        if (!$juzNumber) {
            throw new Exception('رقم الجزء مفقود');
        }

        if (!$ayahFrom) {
            throw new Exception('رقم الآية مفقود');
        }

        // البحث عن السورة
        $surah = Surah::where('name_ar', $surahName)
            ->orWhere('id', intval($surahName))
            ->first();

        if (!$surah) {
            throw new Exception("السورة '$surahName' غير موجودة");
        }

        // البحث عن الجزء
        $juz = Juz::where('id', intval($juzNumber))
            ->orWhere('name', $juzNumber)
            ->first();

        if (!$juz) {
            throw new Exception("الجزء '$juzNumber' غير موجود");
        }

        // التحقق من أن الآية موجودة في السورة والجزء
        $ayahCount = $surah->ayahs()
            ->where('juz_id', $juz->id)
            ->where('ayah_number', '>=', $ayahFrom)
            ->count();

        if ($ayahCount == 0) {
            throw new Exception("الآية $ayahFrom غير موجودة في السورة والجزء المحدد");
        }

        // محاولة تحميل الملف الصوتي إذا تم تحديده
        $audioPath = null;
        if ($filePath && Storage::disk('public')->exists($filePath)) {
            $audioPath = $filePath;
        }

        // إنشاء التسجيل
        StudentSubmission::create([
            'student_id' => $student->id,
            'circle_id' => $student->circle_id,
            'surah_id' => $surah->id,
            'juz_id' => $juz->id,
            'surah' => $surah->name_ar,
            'juz' => $juz->number,
            'ayah_from' => $ayahFrom,
            'ayah_to' => $ayahTo,
            'notes' => $notes,
            'file_path' => $audioPath,
            'status' => 'pending',
        ]);

        $this->successCount++;
    }

    /**
     * Get template CSV content
     */
    public static function getTemplate()
    {
        return "اسم السورة,رقم الجزء,من الآية,إلى الآية,الملاحظات,مسار الملف الصوتي
البقرة,1,1,5,تسجيل نموذجي,
آل عمران,2,1,,
يس,15,1,50,تسجيل الآيات من 1 إلى 50,";
    }
}
