<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('circles', function (Blueprint $table) {
            $table->id();

            // اسم الحلقة
            $table->string('name');

            // الجهة (مسجد / مدرسة / جامعة) - اختياري
            $table->foreignId('organization_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            // نوع الحلقة
            $table->enum('type', ['onsite', 'online', 'hybrid'])
                ->default('onsite');

            // مستوى الحلقة (نصي الآن)
            $table->string('level')->nullable();

            // عدد الطلاب الأقصى
            $table->unsignedInteger('capacity')->nullable();

            // حالة الحلقة
            $table->enum('status', ['active', 'paused', 'archived'])
                ->default('active');

            // وصف مختصر
            $table->text('description')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('circles');
    }
};