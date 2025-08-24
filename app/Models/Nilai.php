<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nilai extends Model
{
    protected $table = 'nilai';

    protected $fillable = [
        'siswa_id',
        'evaluasi_id',
        'nilai',
        'status',
        'feedback',
        'tanggal_dinilai',
    ];

    public function siswa()
    {
        return $this->belongsTo(SiswaProfile::class, 'siswa_id');
    }

    public function evaluasiPembelajaran()
    {
        return $this->belongsTo(EvaluasiPembelajaran::class, 'evaluasi_id');
    }
}
