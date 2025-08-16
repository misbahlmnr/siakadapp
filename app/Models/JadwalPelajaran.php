<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JadwalPelajaran extends Model
{
    protected $table = 'jadwal_pelajaran';

    protected $fillable = [
        'kelas_id',
        'matpel_id',
        'guru_id',
        'hari',
        'jam_mulai',
        'jam_selesai',
        'semester',
        'tahun_ajaran',
    ];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function mataPelajaran()
    {
        return $this->belongsTo(MataPelajaran::class, 'matpel_id');
    }

    public function guru()
    {
        return $this->belongsTo(GuruProfile::class);
    }

    public function materi()
    {
        return $this->hasMany(MateriPembelajaran::class, 'jadwal_id');
    }

    public function evaluasiPembelajaran()
    {
        return $this->hasMany(EvaluasiPembelajaran::class, 'jadwal_id');
    }

    public function nilai()
    {
        return $this->hasMany(Nilai::class, 'jadwal_id');
    }

    public function absensi()
    {
        return $this->hasMany(Absensi::class, 'jadwal_id');
    }
}
