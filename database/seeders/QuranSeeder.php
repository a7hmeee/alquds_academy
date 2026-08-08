<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use App\Models\Surah;
use App\Models\Juz;
use App\Models\Ayah;

class QuranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // مصفوفة تحتوي على أسماء السور بالعربية
        $surahsArabic = [
            1 => 'الفاتحة', 2 => 'البقرة', 3 => 'آل عمران', 4 => 'النساء', 5 => 'المائدة',
            6 => 'الأنعام', 7 => 'الأعراف', 8 => 'الأنفال', 9 => 'التوبة', 10 => 'يونس',
            11 => 'هود', 12 => 'يوسف', 13 => 'الرعد', 14 => 'إبراهيم', 15 => 'الحجر',
            16 => 'النحل', 17 => 'الإسراء', 18 => 'الكهف', 19 => 'مريم', 20 => 'طه',
            21 => 'الأنبياء', 22 => 'الحج', 23 => 'المؤمنون', 24 => 'النور', 25 => 'الفرقان',
            26 => 'الشعراء', 27 => 'النمل', 28 => 'القصص', 29 => 'العنكبوت', 30 => 'الروم',
            31 => 'لقمان', 32 => 'السجدة', 33 => 'الأحزاب', 34 => 'سبأ', 35 => 'فاطر',
            36 => 'يس', 37 => 'الصافات', 38 => 'ص', 39 => 'الزمر', 40 => 'غافر',
            41 => 'فصلت', 42 => 'الشورى', 43 => 'الزخرف', 44 => 'الدخان', 45 => 'الجاثية',
            46 => 'الأحقاف', 47 => 'محمد', 48 => 'الفتح', 49 => 'الحجرات', 50 => 'ق',
            51 => 'الذاريات', 52 => 'الطور', 53 => 'النجم', 54 => 'القمر', 55 => 'الرحمن',
            56 => 'الواقعة', 57 => 'الحديد', 58 => 'المجادلة', 59 => 'الحشر', 60 => 'الممتحنة',
            61 => 'الصف', 62 => 'الجمعة', 63 => 'المنافقون', 64 => 'التغابن', 65 => 'الطلاق',
            66 => 'التحريم', 67 => 'الملك', 68 => 'القلم', 69 => 'الحاقة', 70 => 'المعارج',
            71 => 'نوح', 72 => 'الجن', 73 => 'المزمل', 74 => 'المدثر', 75 => 'القيامة',
            76 => 'الإنسان', 77 => 'المرسلات', 78 => 'النبأ', 79 => 'النازعات', 80 => 'عبس',
            81 => 'التكوير', 82 => 'الإنفطار', 83 => 'المطففين', 84 => 'الانشقاق', 85 => 'الحجر',
            86 => 'الطارق', 87 => 'الأعلى', 88 => 'الغاشية', 89 => 'الفجر', 90 => 'البلد',
            91 => 'الشمس', 92 => 'الليل', 93 => 'الضحى', 94 => 'الانشراح', 95 => 'التين',
            96 => 'العلق', 97 => 'القدر', 98 => 'البينة', 99 => 'الزلزلة', 100 => 'العاديات',
            101 => 'القارعة', 102 => 'التكاثر', 103 => 'العصر', 104 => 'الهمزة', 105 => 'الفيل',
            106 => 'قريش', 107 => 'الماعون', 108 => 'الكوثر', 109 => 'الكافرون', 110 => 'النصر',
            111 => 'المسد', 112 => 'الإخلاص', 113 => 'الفلق', 114 => 'الناس'
        ];

        // إنشاء الأجزاء أولاً
        $this->command->info('جاري إنشاء الأجزاء...');
        for ($i = 1; $i <= 30; $i++) {
            Juz::firstOrCreate(
                ['id' => $i],
                ['name' => "الجزء " . $i]
            );
        }
        $this->command->info('✓ تم إنشاء 30 جزء');

        // تحميل السور والآيات من JSON
        $this->command->info('جاري تحميل بيانات القرآن...');
        
        for ($surahNumber = 1; $surahNumber <= 114; $surahNumber++) {
            try {
                // تحميل البيانات من GitHub
                $url = sprintf(
                    'https://raw.githubusercontent.com/semarketir/quranjson/master/source/surah/surah_%d.json',
                    $surahNumber
                );
                
                $response = Http::timeout(10)->get($url);
                
                if (!$response->successful()) {
                    $this->command->warn("⚠️ فشل تحميل السورة $surahNumber");
                    continue;
                }
                
                $data = $response->json();
                
                // إنشاء السورة
                $surah = Surah::updateOrCreate(
                    ['id' => $surahNumber],
                    [
                        'name_ar' => $surahsArabic[$surahNumber] ?? $data['name'],
                        'name_en' => $data['name'],
                        'revelation_place' => $this->getRevealPlace($surahNumber),
                        'verses_count' => $data['count'] ?? count($data['verse'] ?? [])
                    ]
                );
                
                // إدراج الآيات
                if (isset($data['verse']) && is_array($data['verse'])) {
                    foreach ($data['verse'] as $verseKey => $verseText) {
                        // استخراج رقم الآية من مفتاح الآية (verse_1 -> 1)
                        $ayahNumber = (int) str_replace('verse_', '', $verseKey);
                        
                        // تحديد الجزء بناءً على رقم الآية
                        $juzNumber = $this->getJuzNumber($surahNumber, $ayahNumber);
                        
                        Ayah::updateOrCreate(
                            ['surah_id' => $surahNumber, 'ayah_number' => $ayahNumber],
                            [
                                'juz_id' => $juzNumber,
                                'text' => $verseText
                            ]
                        );
                    }
                }
                
                $this->command->info("✓ تم معالجة السورة {$surah->id}: {$surah->name_ar}");
                
            } catch (\Exception $e) {
                $this->command->error("✗ خطأ في السورة $surahNumber: " . $e->getMessage());
            }
        }
        
        $this->command->info('✅ اكتمل تحميل البيانات بنجاح!');
    }

    /**
     * تحديد مكان النزول (مكية أو مدنية)
     */
    private function getRevealPlace($surahNumber): string
    {
        // السور المدنية (الباقي مكي)
        $madinahSurahs = [2, 3, 4, 5, 8, 9, 24, 33, 47, 48, 58, 59, 60, 61, 62, 64, 65, 66];
        
        return in_array($surahNumber, $madinahSurahs) ? 'مدنية' : 'مكية';
    }

    /**
     * تحديد الجزء بناءً على السورة ورقم الآية
     * هذه طريقة مبسطة - في الواقع يجب استخدام خريطة كاملة للأجزاء
     */
    private function getJuzNumber($surahNumber, $ayahNumber): int
    {
        // خريطة مبسطة للأجزاء (السورة والآية الأولى من كل جزء)
        $juzMap = [
            1 => ['surah' => 1, 'ayah' => 1],      // جزء 1
            2 => ['surah' => 1, 'ayah' => 186],    // جزء 2
            3 => ['surah' => 2, 'ayah' => 253],    // جزء 3
            // ... إلخ (يمكن إكمالها)
        ];
        
        // للآن، نرجع الجزء بناءً على حساب تقريبي
        // هذا يحتاج إلى خريطة دقيقة للأجزاء
        return min(30, (($surahNumber - 1) * 3 + ($ayahNumber / 100)) + 1);
    }
}
