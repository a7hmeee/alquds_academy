<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('student_submissions', function (Blueprint $table) {
            // إضافة الأعمدة الجديدة فقط إذا لم تكن موجودة
            if (!Schema::hasColumn('student_submissions', 'surah_id')) {
                $table->unsignedBigInteger('surah_id')->nullable()->after('juz');
            }
            if (!Schema::hasColumn('student_submissions', 'juz_id')) {
                $table->unsignedBigInteger('juz_id')->nullable()->after('juz');
            }
            if (!Schema::hasColumn('student_submissions', 'ayah_from')) {
                $table->integer('ayah_from')->nullable()->after('juz_id');
            }
            if (!Schema::hasColumn('student_submissions', 'ayah_to')) {
                $table->integer('ayah_to')->nullable()->after('ayah_from');
            }
            if (!Schema::hasColumn('student_submissions', 'self_rating')) {
                $table->integer('self_rating')->nullable()->comment('تقييم الطالب لنفسه')->after('ayah_to');
            }
            if (!Schema::hasColumn('student_submissions', 'self_notes')) {
                $table->text('self_notes')->nullable()->comment('ملاحظات الطالب على تسجيله')->after('self_rating');
            }
        });
    }

    public function down(): void
    {
        Schema::table('student_submissions', function (Blueprint $table) {
            $table->dropColumn(['surah_id', 'juz_id', 'ayah_from', 'ayah_to', 'self_rating', 'self_notes']);
        });
    }
};

