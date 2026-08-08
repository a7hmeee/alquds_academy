<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('student_progress', function (Blueprint $table) {
            // جعل circle_id nullable
            $table->foreignId('circle_id')
                ->nullable()
                ->change();
        });
    }

    public function down(): void
    {
        Schema::table('student_progress', function (Blueprint $table) {
            // إرجاع circle_id إلى الحالة السابقة
            $table->foreignId('circle_id')
                ->nullable(false)
                ->change();
        });
    }
};
