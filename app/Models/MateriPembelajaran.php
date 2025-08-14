<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class MateriPembelajaran extends Model
{
    protected $table = 'materi_pembelajaran';

    protected $fillable = [
        'jadwal_id',
        'guru_id',
        'pertemuan_ke',
        'judul',
        'deskripsi',
        'file_path',
        'link_file',
    ];

    public function jadwal()
    {
        return $this->belongsTo(JadwalPelajaran::class, 'jadwal_id');
    }

    public function guru()
    {
        return $this->belongsTo(GuruProfile::class);
    }

    public function getFilePathAttribute($value)
    {
        if ($value) {
            return Storage::url($value);
        }
        return null;
    }
}
