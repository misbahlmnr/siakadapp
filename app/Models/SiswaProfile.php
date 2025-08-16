<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiswaProfile extends Model
{
    protected $fillable = [
        'user_id',
        'kelas_id',
        'nis',
        'nisn',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'no_hp',
        'angkatan',
        'status',
        'nama_ortu',
        'kontak_ortu',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function pengumpulanTugas()
    {
        return $this->hasMany(PengumpulanTugas::class);
    }

    public function nilai()
    {
        return $this->hasMany(Nilai::class);
    }

    public function absensi()
    {
        return $this->hasMany(Absensi::class);
    }

    public function rekomendasiMateri()
    {
        return $this->hasMany(RekomendasiMateri::class);
    }

    public function rekomendasiMateriManual()
    {
        return $this->hasMany(RekomendasiMateriManual::class);
    }
}
