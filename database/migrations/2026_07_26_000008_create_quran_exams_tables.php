<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quran_exams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('circle_id')->constrained('circles')->cascadeOnDelete();
            $table->foreignId('teacher_id')->constrained('teacher_profiles')->cascadeOnDelete();
            $table->string('title');
            $table->string('exam_type'); // surah, juz, multiple_surahs, review, oral, tajweed, random
            $table->foreignId('surah_id')->nullable()->constrained('surahs')->nullOnDelete();
            $table->foreignId('juz_id')->nullable()->constrained('juz')->nullOnDelete();
            $table->unsignedSmallInteger('total_score')->default(100);
            $table->unsignedSmallInteger('passing_score')->default(70);
            $table->date('exam_date');
            $table->string('status')->default('planned'); // planned, in_progress, completed, cancelled
            $table->text('instructions')->nullable();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['circle_id', 'status']);
            $table->index('exam_date');
        });

        Schema::create('quran_exam_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quran_exam_id')->constrained()->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('student_profiles')->cascadeOnDelete();
            $table->unsignedSmallInteger('score')->nullable();
            $table->decimal('percentage', 5, 2)->nullable();
            $table->boolean('passed')->nullable();
            $table->unsignedSmallInteger('tajweed_score')->nullable();
            $table->unsignedSmallInteger('memorization_score')->nullable();
            $table->text('teacher_notes')->nullable();
            $table->string('status')->default('pending'); // pending, completed, absent
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->unique(['quran_exam_id', 'student_id']);
            $table->index(['student_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quran_exam_results');
        Schema::dropIfExists('quran_exams');
    }
};
