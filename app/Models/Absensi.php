<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    protected $table = 'absensi';

    protected $fillable = [
        'jadwal_id',
        'siswa_id',
        'pertemuan_ke',
        'tanggal',
        'status',
        'semester',
        'tahun_ajaran',
    ];

    public function jadwal()
    {
        return $this->belongsTo(JadwalPelajaran::class, 'jadwal_id');
    }

    public function siswa()
    {
        return $this->belongsTo(SiswaProfile::class, 'siswa_id');
    }
}
