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
        Schema::create('surahs', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
            
            $table->bigIncrements('id');
            $table->string('name_ar')->comment('اسم السورة بالعربية');
            $table->string('name_en')->comment('اسم السورة بالإنجليزية');
            $table->enum('revelation_place', ['مكية', 'مدنية'])->comment('مكية أو مدنية');
            $table->integer('verses_count')->unsigned()->comment('عدد الآيات');
            $table->timestamps();

            // Indexes
            $table->index('name_ar');
            $table->index('revelation_place');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surahs');
    }
};
