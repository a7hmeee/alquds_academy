<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('student_profiles', function (Blueprint $table) {
            $table->id();

            // ===== ربط المستخدم =====
            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            // ===== بيانات أساسية =====
            $table->string('full_name');
            $table->string('photo')->nullable();
            $table->date('birth_date')->nullable();
            $table->enum('gender', ['male', 'female'])->nullable();
            $table->string('nationality')->nullable();

            // ===== معلومات تعليمية =====
            $table->string('level')->nullable(); 
            // مبتدئ / متوسط / متقدم
            $table->string('school')->nullable(); 
            // مدرسة / جامعة / مسجد / مستقل
            $table->string('education_stage')->nullable(); 
            // ابتدائي / إعدادي / ثانوي / جامعي

            // ===== معلومات قرآنية =====
            $table->string('memorization_level')->nullable(); 
            // لا يحفظ / جزء / عدة أجزاء / ختمة
            $table->string('tajweed_level')->nullable(); 
            // ضعيف / متوسط / جيد / ممتاز
            $table->string('current_juz')->nullable(); 
            // الجزء الحالي
            $table->string('current_surah')->nullable(); 
            // السورة الحالية
            $table->integer('current_ayah')->nullable(); 
            // رقم الآية

            // ===== إعدادات الطالب =====
            $table->boolean('is_smart_mode')->default(false);
            // طالب ذكي (يستخدم أدوات AI)
            $table->boolean('needs_assistance')->default(false);
            // يحتاج مساعدة إضافية

            // ===== تواصل =====
            $table->string('phone')->nullable();
            $table->string('guardian_name')->nullable();
            $table->string('guardian_phone')->nullable();

            // ===== حالة الطالب =====
            $table->enum('status', ['active', 'paused', 'archived'])
                ->default('active');

            // ===== ملاحظات =====
            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_profiles');
    }
};