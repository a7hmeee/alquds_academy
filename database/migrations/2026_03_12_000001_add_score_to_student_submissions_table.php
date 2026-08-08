<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('student_submissions', function (Blueprint $table) {
            if (!Schema::hasColumn('student_submissions', 'score')) {
                $table->integer('score')->nullable()->after('rating')->comment('تقييم المعلم من 0 إلى 100');
            }
        });
    }

    public function down(): void
    {
        Schema::table('student_submissions', function (Blueprint $table) {
            $table->dropColumn('score');
        });
    }
};
