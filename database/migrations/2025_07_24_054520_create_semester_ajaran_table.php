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
        Schema::create('semester_ajaran', function (Blueprint $table) {
            $table->id();
            $table->enum('semester', ['ganjil', 'genap']);
            $table->string('tahun_ajaran');
            $table->boolean('status_aktif')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('semester_ajaran');
    }
};
