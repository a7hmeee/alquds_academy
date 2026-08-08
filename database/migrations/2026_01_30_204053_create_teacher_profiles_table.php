<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('teacher_profiles', function (Blueprint $table) {
            $table->id();

            // الحساب
            $table->foreignId('user_id')
                  ->constrained()
                  ->cascadeOnDelete();

            // الصورة
            $table->string('photo')->nullable();

            // الدرجة العلمية
            $table->enum('academic_degree', [
                'hafiz',
                'ijazah',
                'bachelor',
                'master',
                'doctorate',
            ]);

            // سنوات الخبرة
            $table->unsignedTinyInteger('years_of_experience')
                  ->nullable();

            // تخصص المعلم
            $table->string('specialization')->nullable();
            // مثال: تحفيظ / تجويد / إجازات

            // روايات (نص بسيط الآن)
            $table->string('riwayat')->nullable();
            // مثال: حفص / ورش

            // لغة التدريس
            $table->string('teaching_language')->default('ar');

            // الجنس (اختياري)
            $table->enum('gender', ['male', 'female'])->nullable();

            // نبذة
            $table->text('bio')->nullable();

            // الحالة
            $table->enum('status', ['active', 'paused'])
                  ->default('active');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teacher_profiles');
    }
};