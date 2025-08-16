<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class MateriPembelajaran extends Model
{
    protected $table = 'materi_pelajaran';

    protected $fillable = [
        'jadwal_id',
        'guru_id',
        'pertemuan_ke',
        'judul_materi',
        'deskripsi',
        'file_materi',
        'link_materi',
        'semester',
        'tahun_ajaran',
    ];

    public function jadwal()
    {
        return $this->belongsTo(JadwalPelajaran::class, 'jadwal_id');
    }

    public function guru()
    {
        return $this->belongsTo(GuruProfile::class);
    }

    public function rekomendasiMateri()
    {
        return $this->hasMany(RekomendasiMateri::class, 'materi_id');
    }

    public function rekomendasiMateriManual()
    {
        return $this->hasMany(RekomendasiMateriManual::class, 'materi_id');
    }

    public function getFilePathAttribute($value)
    {
        if ($value) {
            return Storage::url($value);
        }
        return null;
    }
}
