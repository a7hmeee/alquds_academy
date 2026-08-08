<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('memorization_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('memorization_assignment_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('student_id')->constrained('student_profiles')->cascadeOnDelete();
            $table->foreignId('teacher_id')->constrained('teacher_profiles')->cascadeOnDelete();
            $table->foreignId('circle_id')->constrained('circles')->cascadeOnDelete();
            $table->foreignId('submission_id')->nullable()->constrained('student_submissions')->nullOnDelete();
            $table->string('session_type'); // memorization, review, test, tajweed
            $table->foreignId('surah_id')->constrained('surahs')->cascadeOnDelete();
            $table->foreignId('juz_id')->constrained('juz')->cascadeOnDelete();
            $table->unsignedInteger('ayah_from');
            $table->unsignedInteger('ayah_to');
            $table->date('session_date');
            $table->unsignedSmallInteger('duration_minutes')->nullable();
            $table->unsignedTinyInteger('memorization_score')->nullable();
            $table->unsignedTinyInteger('tajweed_score')->nullable();
            $table->unsignedTinyInteger('fluency_score')->nullable();
            $table->unsignedTinyInteger('total_score')->nullable();
            $table->string('status')->default('completed'); // completed, failed, rescheduled
            $table->text('teacher_notes')->nullable();
            $table->text('student_notes')->nullable();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['student_id', 'session_date']);
            $table->index(['teacher_id', 'session_date']);
            $table->index(['circle_id', 'session_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('memorization_sessions');
    }
};
