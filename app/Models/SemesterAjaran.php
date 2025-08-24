<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SemesterAjaran extends Model
{
    protected $table = 'semester_ajaran';

    protected $fillable = [
        'semester',
        'tahun_ajaran',
        'status_aktif',
    ];

    public function jadwalPelajaran()
    {
        return $this->hasMany(JadwalPelajaran::class);
    }

    public function materiPelajaran()
    {
        return $this->hasMany(MateriPelajaran::class);
    }

    public function evaluasiPembelajaran()
    {
        return $this->hasMany(EvaluasiPembelajaran::class);
    }

    public function absensi()
    {
        return $this->hasMany(Absensi::class);
    }
}
