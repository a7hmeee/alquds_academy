<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CreateStudentImportTemplate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'student:create-import-template';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'إنشاء نموذج CSV لاستيراد الطلاب';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            // التحقق من وجود المجلد
            $templatesDir = storage_path('app/templates');
            if (!is_dir($templatesDir)) {
                mkdir($templatesDir, 0755, true);
            }

            // إنشاء ملف CSV النموذج
            $csvPath = $templatesDir . '/student_import_template.csv';
            
            $csvContent = "الاسم الكامل,البريد الإلكتروني,رقم الهاتف,تاريخ الميلاد,الجنس,اسم ولي الأمر,هاتف ولي الأمر,مستوى التحفظ,مستوى التجويد,اسم السورة / رقم,اسم الجزء / رقم,رقم الآية,الملاحظات\n";
            $csvContent .= "محمد علي,student1@email.com,0501234567,2008-05-15,male,أحمد علي,0505555555,جزء,جيد,البقرة,1,100,ملاحظة نموذجية\n";
            $csvContent .= "فاطمة عمر,student2@email.com,0509876543,2009-03-20,female,سارة عمر,0505000000,ختمة,ممتاز,آل عمران,2,50,\n";
            $csvContent .= "عمر محمد,,0502222222,,male,,,لا يحفظ,,,,\n";

            file_put_contents($csvPath, $csvContent);

            $this->info('✓ تم إنشاء نموذج الاستيراد بنجاح');
            $this->info('📁 المسار: storage/app/templates/student_import_template.csv');

        } catch (\Exception $e) {
            $this->error('❌ خطأ: ' . $e->getMessage());
        }
    }
}
