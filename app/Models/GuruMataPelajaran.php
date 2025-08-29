<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GuruMataPelajaran extends Model
{
    protected $table = 'guru_mata_pelajaran';

    protected $fillable = [
        'guru_id',
        'matpel_id',
    ];

    public function guruProfile()
    {
        return $this->belongsTo(GuruProfile::class, 'guru_id');
    }

    public function mataPelajaran()
    {
        return $this->belongsTo(MataPelajaran::class, 'matpel_id');
    }

    public function jadwalPelajaran()
    {
        return $this->hasMany(JadwalPelajaran::class, 'guru_matpel_id');
    }

    public function materiPelajaran()
    {
        return $this->hasMany(MateriPelajaran::class, 'guru_matpel_id');
    }

    public function evaluasiPembelajaran()
    {
        return $this->hasMany(EvaluasiPembelajaran::class, 'guru_matpel_id');
    }
}
