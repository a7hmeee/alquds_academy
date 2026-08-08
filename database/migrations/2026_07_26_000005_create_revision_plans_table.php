<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('revision_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('student_profiles')->cascadeOnDelete();
            $table->foreignId('teacher_id')->constrained('teacher_profiles')->cascadeOnDelete();
            $table->foreignId('circle_id')->constrained('circles')->cascadeOnDelete();
            $table->string('name');
            $table->date('start_date');
            $table->date('end_date');
            $table->string('status')->default('active'); // active, completed, cancelled
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['student_id', 'status']);
            $table->index(['teacher_id', 'status']);
        });

        Schema::create('revision_plan_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('revision_plan_id')->constrained()->cascadeOnDelete();
            $table->string('assignment_type'); // new_memorization, close_revision, far_revision, consolidation
            $table->foreignId('surah_id')->constrained('surahs')->cascadeOnDelete();
            $table->foreignId('juz_id')->constrained('juz')->cascadeOnDelete();
            $table->unsignedInteger('ayah_from');
            $table->unsignedInteger('ayah_to');
            $table->date('scheduled_date')->nullable();
            $table->unsignedSmallInteger('repetition_target')->default(1);
            $table->string('status')->default('pending'); // pending, completed, skipped
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->index(['revision_plan_id', 'status']);
            $table->index('scheduled_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('revision_plan_items');
        Schema::dropIfExists('revision_plans');
    }
};
