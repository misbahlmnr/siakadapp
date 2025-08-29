<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JadwalPelajaran extends Model
{
    protected $table = 'jadwal_pelajaran';

    protected $fillable = [
        'kelas_id',
        'guru_matpel_id',
        'semester_ajaran_id',
        'hari',
        'jam_mulai',
        'jam_selesai',
    ];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function guruMatpel()
    {
        return $this->belongsTo(GuruMataPelajaran::class, 'guru_matpel_id');
    }

    public function materi()
    {
        return $this->hasMany(MateriPelajaran::class, 'jadwal_id');
    }

    public function evaluasiPembelajaran()
    {
        return $this->hasMany(EvaluasiPembelajaran::class, 'jadwal_id');
    }

    public function absensi()
    {
        return $this->hasMany(Absensi::class, 'jadwal_id');
    }

    public function semesterAjaran()
    {
        return $this->belongsTo(SemesterAjaran::class);
    }
}
