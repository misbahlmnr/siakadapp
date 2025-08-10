<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TugasUjian extends Model
{
    protected $table = 'tugas_ujian';

    protected $fillable = [
        'jadwal_id',
        'jenis',
        'judul',
        'deskripsi',
        'deadline',
        'file_path',
    ];

    public function jadwal()
    {
        return $this->belongsTo(JadwalPelajaran::class, 'jadwal_id');
    }

    public function nilaiSiswa()
    {
        return $this->hasMany(NilaiSiswa::class);
    }
}
