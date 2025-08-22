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
        Schema::create('pengumpulan_tugas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evaluasi_id')->constrained('evaluasi_pembelajaran')->onDelete('cascade');
            $table->foreignId('siswa_id')->constrained('siswa_profiles')->onDelete('cascade');
            $table->string('file_jawaban')->nullable();
            $table->string('link_jawaban')->nullable();
            $table->enum('status', ['dikumpulkan', 'telat', 'belum_dikumpulkan'])->default('belum_dikumpulkan');
            $table->datetime('waktu_pengumpulan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengumpulan_tugas');
    }
};
