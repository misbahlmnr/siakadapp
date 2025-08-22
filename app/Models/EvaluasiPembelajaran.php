<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EvaluasiPembelajaran extends Model
{
    protected $table = 'evaluasi_pembelajaran';

    protected $fillable = [
        'jadwal_id',
        'guru_id',
        'judul',
        'deskripsi',
        'jenis',
        'waktu_mulai',
        'waktu_selesai',
        'link_soal',
        'file_soal',
        'semester',
        'tahun_ajaran',
    ];

    public function jadwal()
    {
        return $this->belongsTo(JadwalPelajaran::class, 'jadwal_id');
    }

    public function pengumpulanTugas()
    {
        return $this->hasMany(PengumpulanTugas::class, 'evaluasi_id');
    }

    public function guru()
    {
        return $this->belongsTo(GuruProfile::class, 'guru_id');
    }

    public function nilai()
    {
        return $this->hasMany(Nilai::class, 'evaluasi_id');
    }

    public function rekomendasiMateri()
    {
        return $this->hasMany(RekomendasiMateri::class, 'evaluasi_id');
    }

    public function rekomendasiMateriManual()
    {
        return $this->hasMany(RekomendasiMateriManual::class, 'evaluasi_id');
    }
}
