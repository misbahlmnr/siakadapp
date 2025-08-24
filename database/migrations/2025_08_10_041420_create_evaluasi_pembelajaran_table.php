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
        Schema::create('evaluasi_pembelajaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jadwal_id')->constrained('jadwal_pelajaran')->onDelete('cascade');
            $table->foreignId('guru_id')->constrained('guru_profiles')->onDelete('cascade');
            $table->foreignId('semester_ajaran_id')->constrained('semester_ajaran')->onDelete('cascade');
            $table->string('judul');
            $table->text('deskripsi')->nullable();
            $table->enum('jenis', ['tugas', 'kuis', 'ujian'])->default('tugas');
            $table->datetime('waktu_mulai');
            $table->datetime('waktu_selesai');
            $table->string('link_soal')->nullable();
            $table->string('file_soal')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluasi_pembelajaran');
    }
};
