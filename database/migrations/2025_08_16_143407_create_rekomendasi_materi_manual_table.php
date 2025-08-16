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
        Schema::create('rekomendasi_materi_manual', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswa_profiles')->onDelete('cascade');
            $table->foreignId('evaluasi_id')->constrained('evaluasi_pembelajaran')->onDelete('cascade');
            $table->foreignId('materi_id')->constrained('materi_pelajaran')->onDelete('cascade');
            $table->string('alasan_rekomendasi')->nullable();
            $table->enum('status', ['belum_dibaca', 'dibaca', 'selesai'])->default('belum_dibaca');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rekomendasi_materi_manual');
    }
};
