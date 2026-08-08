<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('student_profiles')->cascadeOnDelete();
            $table->foreignId('circle_id')->constrained('circles')->cascadeOnDelete();
            $table->string('file_path');
            $table->string('image_path')->nullable();
            $table->text('notes')->nullable();
            $table->enum('status', ['pending','reviewed','accepted','needs_work'])->default('pending');
            $table->foreignId('reviewed_by')->nullable()->constrained('teacher_profiles')->nullOnDelete();
            $table->text('review_notes')->nullable();
            $table->integer('rating')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_submissions');
    }
};
