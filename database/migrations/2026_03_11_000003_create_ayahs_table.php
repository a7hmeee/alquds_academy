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
        Schema::create('ayahs', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
            
            $table->bigIncrements('id');
            
            // Foreign Keys
            $table->unsignedBigInteger('surah_id')->comment('رقم السورة');
            $table->unsignedBigInteger('juz_id')->comment('رقم الجزء');
            
            // Columns
            $table->integer('ayah_number')->unsigned()->comment('رقم الآية في السورة');
            $table->longText('text')->comment('نص الآية');
            
            $table->timestamps();

            // Indexes
            $table->index('surah_id');
            $table->index('juz_id');
            $table->unique(['surah_id', 'ayah_number']);
            
            // Constraints
            $table->foreign('surah_id')
                  ->references('id')
                  ->on('surahs')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
            
            $table->foreign('juz_id')
                  ->references('id')
                  ->on('juz')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ayahs');
    }
};
