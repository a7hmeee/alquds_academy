<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('circle_teachers', function (Blueprint $table) {
            $table->id();

            $table->foreignId('circle_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('teacher_id')
                ->constrained('teacher_profiles')
                ->cascadeOnDelete();

            // دور المعلّم داخل الحلقة
            $table->enum('role', ['primary', 'assistant'])
                ->default('primary');

            // حالة المعلّم في الحلقة
            $table->enum('status', ['active', 'paused'])
                ->default('active');

            $table->timestamps();

            // منع التكرار
            $table->unique(['circle_id', 'teacher_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('circle_teachers');
    }
};