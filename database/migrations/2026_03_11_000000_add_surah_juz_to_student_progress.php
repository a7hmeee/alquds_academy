<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('student_progress', function (Blueprint $table) {
            // إضافة أعمدة جديدة
            $table->foreignId('surah_id')
                ->nullable()
                ->after('student_id')
                ->constrained('surahs')
                ->nullOnDelete();

            $table->foreignId('juz_id')
                ->nullable()
                ->after('surah_id')
                ->constrained('juz')
                ->nullOnDelete();

            // تحويل ayah إلى معرّف آية بدلاً من رقم
            // نحافظ على العمود الحالي للتوافقية
            // سيتم استخدام surah_id + juz_id + ayah معاً
        });
    }

    public function down(): void
    {
        Schema::table('student_progress', function (Blueprint $table) {
            $table->dropForeignKeyIfExists(['surah_id']);
            $table->dropForeignKeyIfExists(['juz_id']);
            $table->dropColumn(['surah_id', 'juz_id']);
        });
    }
};
