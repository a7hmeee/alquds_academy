<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('student_submissions', function (Blueprint $table) {
            $table->string('surah')->nullable()->after('notes');
            $table->integer('ayah')->nullable()->after('surah');
            $table->string('juz')->nullable()->after('ayah');
        });
    }

    public function down(): void
    {
        Schema::table('student_submissions', function (Blueprint $table) {
            $table->dropColumn(['surah', 'ayah', 'juz']);
        });
    }
};
