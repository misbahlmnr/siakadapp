<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengumpulanTugas extends Model
{
    protected $table = 'pengumpulan_tugas';

    protected $fillable = [
        'evaluasi_id',
        'siswa_id',
        'file_jawaban',
        'link_jawaban',
        'nilai',
        'tanggal_pengumpulan',
    ];

    public function evaluasiPembelajaran()
    {
        return $this->belongsTo(EvaluasiPembelajaran::class, 'evaluasi_id');
    }

    public function siswa()
    {
        return $this->belongsTo(SiswaProfile::class, 'siswa_id');
    }
}
