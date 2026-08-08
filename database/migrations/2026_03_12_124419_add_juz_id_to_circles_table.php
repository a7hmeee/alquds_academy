<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('circles', function (Blueprint $table) {
            $table->unsignedBigInteger('juz_id')->nullable()->after('description');
            $table->foreign('juz_id')->references('id')->on('juz')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('circles', function (Blueprint $table) {
            $table->dropForeign(['juz_id']);
            $table->dropColumn('juz_id');
        });
    }
};
