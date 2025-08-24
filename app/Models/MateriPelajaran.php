<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class MateriPelajaran extends Model
{
    protected $table = 'materi_pelajaran';

    protected $fillable = [
        'jadwal_id',
        'guru_id',
        'semester_ajaran_id',
        'pertemuan_ke',
        'judul_materi',
        'deskripsi',
        'file_materi',
        'link_materi',
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

    public function adaptiveRules()
    {
        return $this->hasMany(AdaptiveRules::class);
    }

    public function semesterAjaran()
    {
        return $this->belongsTo(SemesterAjaran::class);
    }

    public function getFilePathAttribute($value)
    {
        if ($value) {
            return Storage::url($value);
        }
        return null;
    }
}
