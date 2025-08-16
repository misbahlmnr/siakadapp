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
        Schema::create('nilai', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswa_profiles')->onDelete('cascade');
            $table->foreignId('jadwal_id')->constrained('jadwal_pelajaran')->onDelete('cascade');
            $table->foreignId('guru_id')->constrained('guru_profiles')->onDelete('cascade');
            $table->foreignId('evaluasi_id')->constrained('evaluasi_pembelajaran')->onDelete('cascade');
            $table->enum('jenis', ['tugas', 'kuis', 'ujian'])->default('tugas');
            $table->string('nilai')->nullable();
            $table->string('semester')->nullable();
            $table->string('tahun_ajaran')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nilai');
    }
};
