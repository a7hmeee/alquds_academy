<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_achievements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('student_profiles')->cascadeOnDelete();
            $table->string('achievement_type'); // surah_completed, juz_completed, attendance_milestone, streak, improvement, exam_passed
            $table->string('title');
            $table->text('description')->nullable();
            $table->foreignId('surah_id')->nullable()->constrained('surahs')->nullOnDelete();
            $table->foreignId('juz_id')->nullable()->constrained('juz')->nullOnDelete();
            $table->timestamp('achieved_at');
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index(['student_id', 'achievement_type']);
        });

        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('student_profiles')->cascadeOnDelete();
            $table->foreignId('student_achievement_id')->nullable()->constrained()->nullOnDelete();
            $table->string('certificate_type'); // juz_completion, surah_completion, course, exam
            $table->string('title');
            $table->timestamp('issued_at');
            $table->foreignId('issued_by')->constrained('users')->cascadeOnDelete();
            $table->string('verification_code')->unique();
            $table->string('file_path')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index(['student_id', 'certificate_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('certificates');
        Schema::dropIfExists('student_achievements');
    }
};
