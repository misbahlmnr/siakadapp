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
        Schema::create('materi_pelajaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jadwal_id')->constrained('jadwal_pelajaran')->onDelete('cascade');
            $table->foreignId('guru_id')->constrained('guru_profiles')->onDelete('cascade');
            $table->foreignId('semester_ajaran_id')->constrained('semester_ajaran')->onDelete('cascade');
            $table->unsignedInteger('pertemuan_ke');
            $table->string('judul_materi');
            $table->text('deskripsi')->nullable();
            $table->string('file_materi')->nullable();
            $table->string('link_materi')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materi_pelajaran');
    }
};
