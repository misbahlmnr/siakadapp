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
        Schema::create('guru_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('nip')->unique()->nullable();
            $table->string('mapel')->nullable();
            $table->string('no_telp')->nullable();
            $table->text('alamat')->nullable();
            $table->enum('status_guru', ['pns', 'honorer'])->default('pns');
            $table->date('tanggal_masuk')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guru_profiles');
    }
};
