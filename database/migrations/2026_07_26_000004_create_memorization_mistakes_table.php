<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('memorization_mistakes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('memorization_session_id')->constrained()->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('student_profiles')->cascadeOnDelete();
            $table->foreignId('surah_id')->constrained('surahs')->cascadeOnDelete();
            $table->unsignedInteger('ayah_number');
            $table->string('mistake_type'); // memorization, tajweed, haraka, madd, ghunnah, makhraj, waqf_ibtida, omission, repetition, hesitation, other
            $table->string('severity')->default('minor'); // minor, moderate, major, critical
            $table->string('word_text')->nullable();
            $table->string('correct_text')->nullable();
            $table->text('teacher_note')->nullable();
            $table->boolean('is_resolved')->default(false);
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();

            $table->index(['student_id', 'mistake_type']);
            $table->index(['student_id', 'surah_id']);
            $table->index('is_resolved');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('memorization_mistakes');
    }
};
