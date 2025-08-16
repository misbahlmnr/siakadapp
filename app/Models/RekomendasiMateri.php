<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RekomendasiMateri extends Model
{
    protected $table = 'rekomendasi_materi';

    protected $fillable = [
        'siswa_id',
        'evaluasi_id',
        'materi_id',
        'alasan_rekomendasi',
        'status',
    ];

    public function siswa()
    {
        return $this->belongsTo(SiswaProfile::class, 'siswa_id');
    }

    public function evaluasiPembelajaran()
    {
        return $this->belongsTo(EvaluasiPembelajaran::class, 'evaluasi_id');
    }

    public function materi()
    {
        return $this->belongsTo(MateriPembelajaran::class, 'materi_id');
    }
}
