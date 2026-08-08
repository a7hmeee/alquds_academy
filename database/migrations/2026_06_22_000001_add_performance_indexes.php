<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('student_submissions', function (Blueprint $table) {
            $table->index(['student_id', 'circle_id']);
            $table->index(['surah_id', 'juz_id']);
            $table->index('status');
            $table->index('score');
            $table->index('created_at');
        });

        Schema::table('student_progress', function (Blueprint $table) {
            $table->index(['student_id', 'circle_id', 'created_at']);
            $table->index('surah_id');
            $table->index('juz_id');
        });

        Schema::table('ayahs', function (Blueprint $table) {
            $table->index(['surah_id', 'juz_id']);
            $table->index('ayah_number');
        });

        Schema::table('circles', function (Blueprint $table) {
            $table->index('status');
            $table->index('organization_id');
        });

        Schema::table('student_profiles', function (Blueprint $table) {
            $table->index('status');
            $table->index('teacher_id');
        });

        Schema::table('teacher_profiles', function (Blueprint $table) {
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::table('student_submissions', function (Blueprint $table) {
            $table->dropIndex(['student_id', 'circle_id']);
            $table->dropIndex(['surah_id', 'juz_id']);
            $table->dropIndex(['status']);
            $table->dropIndex(['score']);
            $table->dropIndex(['created_at']);
        });

        Schema::table('student_progress', function (Blueprint $table) {
            $table->dropIndex(['student_id', 'circle_id', 'created_at']);
            $table->dropIndex(['surah_id']);
            $table->dropIndex(['juz_id']);
        });

        Schema::table('ayahs', function (Blueprint $table) {
            $table->dropIndex(['surah_id', 'juz_id']);
            $table->dropIndex(['ayah_number']);
        });

        Schema::table('circles', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['organization_id']);
        });

        Schema::table('student_profiles', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['teacher_id']);
        });

        Schema::table('teacher_profiles', function (Blueprint $table) {
            $table->dropIndex(['status']);
        });
    }
};
