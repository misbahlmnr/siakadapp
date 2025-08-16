<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nilai extends Model
{
    protected $table = 'nilai';

    protected $fillable = [
        'siswa_id',
        'jadwal_id',
        'guru_id',
        'evaluasi_id',
        'jenis',
        'nilai',
        'semester',
        'tahun_ajaran',
    ];

    public function siswa()
    {
        return $this->belongsTo(SiswaProfile::class, 'siswa_id');
    }

    public function jadwal()
    {
        return $this->belongsTo(JadwalPelajaran::class, 'jadwal_id');
    }

    public function guru()
    {
        return $this->belongsTo(GuruProfile::class, 'guru_id');
    }

    public function evaluasiPembelajaran()
    {
        return $this->belongsTo(EvaluasiPembelajaran::class, 'evaluasi_id');
    }
}
