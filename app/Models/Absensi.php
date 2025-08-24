<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    protected $table = 'absensi';

    protected $fillable = [
        'jadwal_id',
        'siswa_id',
        'semester_ajaran_id',
        'pertemuan_ke',
        'tanggal',
        'status',
    ];

    public function jadwal()
    {
        return $this->belongsTo(JadwalPelajaran::class, 'jadwal_id');
    }

    public function siswa()
    {
        return $this->belongsTo(SiswaProfile::class, 'siswa_id');
    }

    public function semesterAjaran()
    {
        return $this->belongsTo(SemesterAjaran::class);
    }
}
