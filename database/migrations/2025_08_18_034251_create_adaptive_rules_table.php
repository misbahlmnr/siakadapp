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
        Schema::create('adaptive_rules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('matpel_id')->constrained('mata_pelajaran')->onDelete('cascade');
            $table->foreignId('materi_id')->constrained('materi_pelajaran')->onDelete('cascade');
            $table->foreignId('dibuat_oleh')->constrained('users')->onDelete('cascade');
            $table->enum('operator', ['<', '<=', '=', '>=', '>'])->default('=');
            $table->integer('nilai_batas')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('adaptive_rules');
    }
};
