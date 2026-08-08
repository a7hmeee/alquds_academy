<?php

namespace App\Services;

use App\Models\StudentProfile;
use App\Models\StudentProgress;
use App\Models\Surah;
use App\Models\Juz;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Exception;

class StudentImportService
{
    /**
     * استيراد الطلاب من ملف Excel/CSV
     * 
     * @param string $filePath مسار الملف
     * @param array $options خيارات الاستيراد
     * @return array نتيجة الاستيراد
     */
    public function import($filePath, $options = [])
    {
        $result = [
            'success' => 0,
            'failed' => 0,
            'errors' => [],
            'details' => [],
        ];

        try {
            // قراءة الملف
            $spreadsheet = IOFactory::load($filePath);
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();

            // تخطي رأس الجدول (السطر الأول)
            for ($i = 1; $i < count($rows); $i++) {
                $row = $rows[$i];

                // تخطي الصفوف الفارغة
                if (empty(array_filter($row))) {
                    continue;
                }

                try {
                    $studentData = $this->parseRow($row);
                    $this->createStudent($studentData);
                    $result['success']++;
                    $result['details'][] = [
                        'row' => $i + 1,
                        'name' => $studentData['full_name'],
                        'status' => 'نجح',
                    ];
                } catch (Exception $e) {
                    $result['failed']++;
                    $result['errors'][] = [
                        'row' => $i + 1,
                        'error' => $e->getMessage(),
                    ];
                }
            }

        } catch (Exception $e) {
            throw new Exception('خطأ في قراءة الملف: ' . $e->getMessage());
        }

        return $result;
    }

    /**
     * تحليل صف البيانات
     */
    private function parseRow($row)
    {
        // الأعمدة المتوقعة:
        // 0: الاسم الكامل
        // 1: البريد الإلكتروني (اختياري)
        // 2: الهاتف (اختياري)
        // 3: تاريخ الميلاد (اختياري)
        // 4: الجنس (male/female) (اختياري)
        // 5: اسم ولي الأمر (اختياري)
        // 6: هاتف ولي الأمر (اختياري)
        // 7: مستوى التحفظ (اختياري)
        // 8: مستوى التجويد (اختياري)
        // 9: اسم السورة / رقم السورة (اختياري)
        // 10: اسم الجزء / رقم الجزء (اختياري)
        // 11: الآية (اختياري)
        // 12: الملاحظات (اختياري)

        $fullName = trim($row[0] ?? '');
        $email = trim($row[1] ?? '');
        $phone = trim($row[2] ?? '');
        $birthDate = $this->parseDate($row[3] ?? '');
        $gender = trim($row[4] ?? '');
        $guardianName = trim($row[5] ?? '');
        $guardianPhone = trim($row[6] ?? '');
        $memorizationLevel = trim($row[7] ?? '');
        $tajweedLevel = trim($row[8] ?? '');
        $surahInput = trim($row[9] ?? '');
        $juzInput = trim($row[10] ?? '');
        $ayahNumber = $row[11] ?? '';
        $notes = trim($row[12] ?? '');

        // التحقق من الاسم (إلزامي)
        if (empty($fullName)) {
            throw new Exception('الاسم الكامل مفقود');
        }

        // البحث عن السورة
        $surahId = null;
        if (!empty($surahInput)) {
            $surah = Surah::where('name_ar', 'like', '%' . $surahInput . '%')
                ->orWhere('name_en', 'like', '%' . $surahInput . '%')
                ->orWhere('id', $surahInput)
                ->first();
            
            if (!$surah) {
                throw new Exception("السورة '$surahInput' غير موجودة");
            }
            $surahId = $surah->id;
        }

        // البحث عن الجزء
        $juzId = null;
        if (!empty($juzInput)) {
            $juz = Juz::where('name', 'like', '%' . $juzInput . '%')
                ->orWhere('id', $juzInput)
                ->first();
            
            if (!$juz) {
                throw new Exception("الجزء '$juzInput' غير موجود");
            }
            $juzId = $juz->id;
        }

        // التحقق من أن الآية رقم
        if (!empty($ayahNumber) && !is_numeric($ayahNumber)) {
            throw new Exception("رقم الآية يجب أن يكون رقماً");
        }

        return [
            'full_name' => $fullName,
            'email' => $email,
            'phone' => $phone,
            'birth_date' => $birthDate,
            'gender' => $gender,
            'guardian_name' => $guardianName,
            'guardian_phone' => $guardianPhone,
            'memorization_level' => $memorizationLevel,
            'tajweed_level' => $tajweedLevel,
            'surah_id' => $surahId,
            'juz_id' => $juzId,
            'ayah' => !empty($ayahNumber) ? (int)$ayahNumber : null,
            'notes' => $notes,
        ];
    }

    /**
     * تحليل التاريخ من صيغ متعددة
     */
    private function parseDate($dateString)
    {
        if (empty($dateString)) {
            return null;
        }

        // إذا كان رقم Excel date
        if (is_numeric($dateString)) {
            try {
                $date = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($dateString);
                return $date->format('Y-m-d');
            } catch (Exception $e) {
                return null;
            }
        }

        // محاولة تحليل التاريخ
        try {
            $date = \Carbon\Carbon::createFromFormat('Y-m-d', $dateString);
            return $date->format('Y-m-d');
        } catch (Exception $e) {
            try {
                $date = \Carbon\Carbon::createFromFormat('d/m/Y', $dateString);
                return $date->format('Y-m-d');
            } catch (Exception $e2) {
                return null;
            }
        }
    }

    /**
     * إنشاء الطالب
     */
    private function createStudent($studentData)
    {
        // إنشاء الطالب
        $student = StudentProfile::create([
            'full_name' => $studentData['full_name'],
            'phone' => $studentData['phone'] ?: null,
            'birth_date' => $studentData['birth_date'],
            'gender' => $studentData['gender'] ?: null,
            'guardian_name' => $studentData['guardian_name'],
            'guardian_phone' => $studentData['guardian_phone'],
            'memorization_level' => $studentData['memorization_level'] ?: null,
            'tajweed_level' => $studentData['tajweed_level'] ?: null,
            'current_surah' => $studentData['surah_id'],
            'current_juz' => $studentData['juz_id'],
            'current_ayah' => $studentData['ayah'],
            'notes' => $studentData['notes'],
            'status' => 'active',
        ]);

        // إنشاء سجل التقدم إذا كان يحفظ
        if ($studentData['surah_id']) {
            StudentProgress::create([
                'student_id' => $student->id,
                'surah_id' => $studentData['surah_id'],
                'juz_id' => $studentData['juz_id'],
                'ayah' => $studentData['ayah'],
                'notes' => $studentData['notes'],
                'created_by' => auth()->id(),
            ]);
        }

        return $student;
    }
}
